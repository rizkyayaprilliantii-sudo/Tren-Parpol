<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pkl.local'],
            [
                'name'     => 'Admin PKL',
                'password' => Hash::make('password'),
            ]
        );

        $this->importCsv('master_pemilu.csv',     'master_pemilu');
        $this->importCsv('master_kecamatan.csv',  'master_kecamatan');
        $this->importCsv('master_kelurahan.csv',  'master_kelurahan');
        $this->importCsv('master_tps.csv',        'master_tps');
        $this->importCsv('master_partai.csv',     'master_partai');

        $this->importCsv('hasil_suara_2019.csv',    'hasil_suara_2019');
        $this->importCsv('hasil_suara_2024.csv',    'hasil_suara_2024');
        $this->importCsv('hasil_cluster_2019.csv',  'hasil_cluster_2019');
        $this->importCsv('hasil_cluster_2024.csv',  'hasil_cluster_2024');
        $this->importCsv('hasil_perbandingan.csv',  'hasil_perbandingan');
    }

    private function importCsv(string $file, string $table): void
    {
        $path = database_path("seeders/Data/{$file}");
        if (!file_exists($path)) {
            $this->command->warn("File tidak ditemukan: {$file}");
            return;
        }

        DB::table($table)->truncate();
        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows);

        $chunk = [];
        foreach ($rows as $row) {
            $chunk[] = array_combine($header, $row);
            if (count($chunk) >= 1000) {
                DB::table($table)->insert($chunk);
                $chunk = [];
            }
        }
        if ($chunk) DB::table($table)->insert($chunk);

        $this->command->info("✓ {$table} — " . count($rows) . " baris");
    }
}
