<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [];
        foreach ($modules as $key => $value) {

            $service = [
                'name' => $value['name'],
                'val' => $value['val'],
                'type' => $value['type']
            ];
            $service = Setting::create($service);
        }

    }
}
