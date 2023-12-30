<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name'=>'mostafa fahmy','email'=> 'm@m.com','password'=> Hash::make('@@123456'),'rule_id'=>3,'phone'=>'01126713126'],
            ['name'=>'amr khaled','email'=> 'a@a.com','password'=> Hash::make('123456'),'rule_id'=>1,'phone'=>'123456'],
            ['name'=>'taher ghalab','email'=> 't@t.com','password'=> Hash::make('a123456'),'rule_id'=>2,'phone'=>'12345678'],
        ];
        foreach ($users as $user) {
           User::create($user);
        }
    }
}
