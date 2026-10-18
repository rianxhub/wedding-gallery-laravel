<?php

namespace Database\Seeders;

use App\Models\DownloadLog;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

class DownloadLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wedding = Wedding::first();

        if ($wedding) {
            DownloadLog::create([
                'wedding_id' => $wedding->id,
                'drive_file_id' => 'file_img_001_highres',
                'file_name' => 'DSC_0012_Akad_HD.jpg',
                'ip_address' => '192.168.1.15',
                'downloaded_at' => now()->subHours(3),
            ]);

            DownloadLog::create([
                'wedding_id' => $wedding->id,
                'drive_file_id' => 'file_img_002_highres',
                'file_name' => 'DSC_0045_Resepsi_HD.jpg',
                'ip_address' => '114.124.200.5',
                'downloaded_at' => now()->subHour(),
            ]);

            DownloadLog::create([
                'wedding_id' => $wedding->id,
                'drive_file_id' => 'file_zip_all_prewed',
                'file_name' => 'Full_Album_Prewedding.zip',
                'ip_address' => '180.252.112.88',
                'downloaded_at' => now(),
            ]);
        }
    }
}
