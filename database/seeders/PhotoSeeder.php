<?php

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $wedding = Wedding::first();

        if ($wedding) {
            Photo::firstOrCreate(
                ['drive_file_id' => 'CONTOH_FILE_ID_1'],
                [
                    'wedding_id' => $wedding->id,
                    'nama_file' => 'foto-1.jpg',
                    'mime_type' => 'image/jpeg',
                    'thumbnail_url' => 'https://lh3.googleusercontent.com/d/CONTOH_FILE_ID_1=w600-rw',
                ]
            );

            Photo::firstOrCreate(
                ['drive_file_id' => 'CONTOH_FILE_ID_2'],
                [
                    'wedding_id' => $wedding->id,
                    'nama_file' => 'foto-2.jpg',
                    'mime_type' => 'image/jpeg',
                    'thumbnail_url' => 'https://lh3.googleusercontent.com/d/CONTOH_FILE_ID_2=w600-rw',
                ]
            );
        }
    }
}