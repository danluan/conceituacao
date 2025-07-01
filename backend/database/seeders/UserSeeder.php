<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
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
        $user = User::updateOrCreate([
            'name'=> 'admin',
            'email' => 'admin@gup.com',
            'password' => Hash::make('admin1234'),
        ]);

        $profile = Profile::where('name', 'ADMINISTRATOR')->first();
        if ($profile) {
            $user->profiles()->attach($profile->id);
        }
    }
}
