<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip|max:50000', 
        ]);

        $path = $request->file('file')->storeAs('imports', 'data_export.zip');
        $absPath = Storage::path($path);

        $zip = new \ZipArchive;
        if ($zip->open($absPath) === true) {
            $zip->extractTo(database_path('seeders/Data'));
            $zip->close();
        }

        Artisan::call('db:seed', ['--force' => true]);

        return back()->with('success', 'Data berhasil diimpor. ' . Artisan::output());
    }
}
