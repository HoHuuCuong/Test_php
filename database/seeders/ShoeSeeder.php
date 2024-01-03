<?php

namespace Database\Seeders;

use App\Models\Shoe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShoeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Đọc file JSON
        $path = base_path('database/data.json');
        // Kiểm tra xem file có tồn tại không
        if (!file_exists($path)) {
            Log::error("File not found: " . $path);
            return;
        }

        $jsonData = file_get_contents($path);
        $data = json_decode($jsonData, true);

        // Kiểm tra dữ liệu và nhập vào cơ sở dữ liệu
        if (!empty($data['shoes'])) {
            foreach ($data['shoes'] as $shoe) {
                Shoe::create([
                    'image' => $shoe['image'] ?? null,
                    'name' => $shoe['name'],
                    'description' => $shoe['description'] ?? null,
                    'price' => $shoe['price'],
                    'color' => $shoe['color'] ?? null
                ]);
            }
        }
    }
}
