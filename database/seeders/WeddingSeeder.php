<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

class WeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rian = User::where('email', 'rian.aulia@gmail.com')->first();
        $dimas = User::where('email', 'dimas.sarah@gmail.com')->first();

        if ($rian) {
            Wedding::firstOrCreate(
                ['slug' => 'rian-dan-aulia'],
                [
                    'user_id' => $rian->id,
                    'couple_name' => 'Rian & Aulia',
                    'wedding_date' => '2026-12-20',
                    'drive_folder_id' => '1aB2cD3eF4gH5iJ6kL7mN8oP9qR',
                    'cover_drive_file_id' => 'cover_rian_aulia_001',
                    'quote' => 'Two souls, one heart. Bersama melangkah dalam cinta selamanya.',
                    'theme' => 'Rustic Floral',
                ]
            );
        }

        if ($dimas) {
            Wedding::firstOrCreate(
                ['slug' => 'dimas-dan-sarah'],
                [
                    'user_id' => $dimas->id,
                    'couple_name' => 'Dimas & Sarah',
                    'wedding_date' => '2027-01-15',
                    'drive_folder_id' => '2bC3dE4fG5hI6jK7lM8nO9pQ0rS',
                    'cover_drive_file_id' => 'cover_dimas_sarah_002',
                    'quote' => 'Cinta sejati bukan menemukan seseorang yang sempurna, tetapi menyempurnakan bersama.',
                    'theme' => 'Modern Minimalist',
                ]
            );
        }
    }
}