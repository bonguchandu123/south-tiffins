<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'name'       => 'South Tiffins Admin',
            'email'      => 'admin@southtiffins.com',
            'password'   => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('counter_access')->insert([
            'pin'        => '1234',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('categories')->insert([
            ['name_en' => 'Breakfast',   'name_te' => 'అల్పాహారం',       'sort_order' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_en' => 'Tiffin',      'name_te' => 'టిఫిన్',           'sort_order' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_en' => 'Rice Items',  'name_te' => 'అన్నం వంటకాలు',   'sort_order' => 3, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name_en' => 'Drinks',      'name_te' => 'పానీయాలు',         'sort_order' => 4, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}