<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403
        );

        $search = $request->get('search', '');

        $logs = ActivityLog::with('user')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'activity',
                        'like',
                        "%{$search}%"
                    )

                        ->orWhere(
                            'description',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhereHas('user', function ($userQuery) use ($search) {

                            $userQuery
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                );
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.riwayat-aktivitas.index',
            compact('logs', 'search')
        );
    }
}
