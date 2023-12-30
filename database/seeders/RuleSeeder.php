<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rules = ['saler' ,'admin','superAdmin'];
        foreach ($rules as $key => $value) {
            Rule::create([
                'name'=> $value
            ]);
        }
    }
}
