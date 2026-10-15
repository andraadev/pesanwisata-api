<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Danau Toba',
                'slug' => Str::slug('Danau Toba'),
                'location' => 'Sumatera Utara',
                'description' => 'Danau vulkanik terbesar di dunia dengan pemandangan Pulau Samosir yang indah.',
                'image_url' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=800',
            ],
            [
                'name' => 'Candi Borobudur',
                'slug' => Str::slug('Candi Borobudur'),
                'location' => 'Magelang, Jawa Tengah',
                'description' => 'Candi Buddha terbesar di dunia yang terletak di Magelang, Jawa Tengah.',
                'image' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=800',
            ],
            [
                'name' => 'Nusa Penida',
                'slug' => Str::slug('Nusa Penida'),
                'location' => 'Bali',
                'description' => 'Pulau indah di Bali dengan tebing-tebing ikonik dan pantai pasir putih.',
                'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800',
            ],
        ];

        foreach ($destinations as $data) {
            Destination::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
