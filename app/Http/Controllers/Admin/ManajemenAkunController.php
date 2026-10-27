<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManajemenAkunController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->whereIn('role', ['admin', 'pimpinan'])
            ->latest()
            ->paginate(10);

        return view('admin.manajemen-akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.manajemen-akun.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                Rule::in(['admin', 'pimpinan']),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        /*
         * Buat akun baru.
         */
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        /*
         * Catat aktivitas setelah akun berhasil dibuat.
         */
        $this->catatAktivitas(
            'Menambahkan Akun',
            'Menambahkan akun "'
                . $user->name
                . '" dengan role '
                . $this->formatRole($user->role)
                . '.'
        );

        return redirect()
            ->route('admin.manajemen-akun.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $this->pastikanRoleDapatDikelola($user);

        return view('admin.manajemen-akun.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->pastikanRoleDapatDikelola($user);

        return view('admin.manajemen-akun.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->pastikanRoleDapatDikelola($user);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in(['admin', 'pimpinan']),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        /*
         * Simpan data lama sebelum diperbarui.
         * Ini digunakan untuk membuat keterangan riwayat
         * yang lebih jelas.
         */
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRole = $user->role;

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make(
                $validated['password']
            );
        }

        /*
         * Perbarui akun.
         */
        $user->update($data);

        /*
         * Siapkan keterangan perubahan.
         */
        $changes = [];

        if ($oldName !== $user->name) {
            $changes[] =
                'nama: "'
                . $oldName
                . '" menjadi "'
                . $user->name
                . '"';
        }

        if ($oldEmail !== $user->email) {
            $changes[] =
                'email: '
                . $oldEmail
                . ' menjadi '
                . $user->email;
        }

        if ($oldRole !== $user->role) {
            $changes[] =
                'role: '
                . $this->formatRole($oldRole)
                . ' menjadi '
                . $this->formatRole($user->role);
        }

        if ($request->filled('password')) {
            $changes[] = 'password diperbarui';
        }

        $description =
            'Memperbarui akun "'
            . $user->name
            . '".';

        if (!empty($changes)) {
            $description .=
                ' Perubahan: '
                . implode('; ', $changes)
                . '.';
        }

        /*
         * Catat aktivitas setelah perubahan berhasil.
         */
        $this->catatAktivitas(
            'Memperbarui Akun',
            $description
        );

        return redirect()
            ->route('admin.manajemen-akun.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->pastikanRoleDapatDikelola($user);

        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Akun yang sedang digunakan tidak dapat dihapus.'
            );
        }

        /*
         * Simpan informasi akun sebelum dihapus.
         */
        $deletedName = $user->name;
        $deletedRole = $this->formatRole($user->role);

        /*
         * Hapus akun.
         */
        $user->delete();

        /*
         * Catat aktivitas setelah penghapusan berhasil.
         */
        $this->catatAktivitas(
            'Menghapus Akun',
            'Menghapus akun "'
                . $deletedName
                . '" dengan role '
                . $deletedRole
                . '.'
        );

        return redirect()
            ->route('admin.manajemen-akun.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Simpan aktivitas ke tabel activity_logs.
     */
    private function catatAktivitas(
        string $activity,
        string $description
    ): void {
        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Ubah nilai role database menjadi label yang lebih
     * mudah dibaca pada riwayat aktivitas.
     */
    private function formatRole(?string $role): string
    {
        return match ($role) {
            'admin' => 'Admin',
            'pimpinan' => 'Pimpinan',
            default => ucfirst((string) $role),
        };
    }

    private function pastikanRoleDapatDikelola(User $user): void
    {
        abort_unless(
            in_array(
                $user->role,
                ['admin', 'pimpinan'],
                true
            ),
            404
        );
    }
}
