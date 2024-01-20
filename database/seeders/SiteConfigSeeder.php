<?php

namespace Database\Seeders;

use App\Models\SiteConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
          [ "key"=> "purchase_prefix","value"=>"MP"],
          [ "key"=> "purchase_number","value"=> "1"],
          [  "key"=> "order_prefix","value"=>"MS"],
          [ "key"=> "order_number","value"=> "1"],
        ];
        foreach ($data as $key) {
            SiteConfig::create($key);
        }
       
    }
}
