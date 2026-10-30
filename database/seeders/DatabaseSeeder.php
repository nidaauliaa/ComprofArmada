<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Tim Marketing New Armada',
            'email' => 'marketing@newarmada.co.id',
            'password' => Hash::make('marketing789'), 
        ]);
    }
}
