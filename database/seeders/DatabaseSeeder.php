<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ruleSeeder::class,
            UserSeeder::class,
            SiteConfigSeeder::class,
            CustomerSeeder::class,
            StoreSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
