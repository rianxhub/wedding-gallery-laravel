<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rianWedding = Wedding::where('slug', 'rian-dan-aulia')->first();
        $dimasWedding = Wedding::where('slug', 'dimas-dan-sarah')->first();

        if ($rianWedding) {
            Album::firstOrCreate(
                [
                    'wedding_id' => $rianWedding->id,
                    'album_name' => 'Prewedding Photo Shoot',
                ],
                [
                    'drive_subfolder_id' => 'subfolder_prewed_001',
                    'sort_order' => 1,
                ]
            );

            Album::firstOrCreate(
                [
                    'wedding_id' => $rianWedding->id,
                    'album_name' => 'Akad Nikah & Ijab Qabul',
                ],
                [
                    'drive_subfolder_id' => 'subfolder_akad_002',
                    'sort_order' => 2,
                ]
            );

            Album::firstOrCreate(
                [
                    'wedding_id' => $rianWedding->id,
                    'album_name' => 'Resepsi & Pesta Malam',
                ],
                [
                    'drive_subfolder_id' => 'subfolder_resepsi_003',
                    'sort_order' => 3,
                ]
            );
        }

        if ($dimasWedding) {
            Album::firstOrCreate(
                [
                    'wedding_id' => $dimasWedding->id,
                    'album_name' => 'Prewedding Session',
                ],
                [
                    'drive_subfolder_id' => 'subfolder_dimas_prewed',
                    'sort_order' => 1,
                ]
            );

            Album::firstOrCreate(
                [
                    'wedding_id' => $dimasWedding->id,
                    'album_name' => 'Holy Matrimony & Reception',
                ],
                [
                    'drive_subfolder_id' => 'subfolder_dimas_matrimony',
                    'sort_order' => 2,
                ]
            );
        }
    }
}
