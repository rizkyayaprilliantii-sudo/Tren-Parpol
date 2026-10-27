<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CsvImportSeeder extends Seeder
{
    public function run(): void
    {
        $folder = database_path('seeders/Data');

        $files = [
            'master_kelurahan.csv',      // butuh master_kecamatan
            'master_tps.csv',            // butuh master_kelurahan
            'master_partisipasi_2019.csv',
            'master_partisipasi_2024.csv',
            'suara_partai_2019.csv',
            'suara_partai_2024.csv',
            'hasil_suara_2019.csv',
            'hasil_suara_2024.csv',
            'hasil_cluster_2019.csv',
            'hasil_cluster_2024.csv',
            'hasil_perbandingan.csv',
        ];

        foreach ($files as $file) {
            $path = "{$folder}/{$file}";
            if (!file_exists($path)) {
                $this->command->warn("⚠ File tidak ditemukan: {$file}");
                continue;
            }

            $table = str_replace('.csv', '', $file);
            $this->importCsv($path, $table);
        }
    }

    private function importCsv(string $path, string $table): void
    {
        DB::table($table)->truncate(); // Kosongkan dulu

        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows); // Baris pertama = nama kolom

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
