<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::where('email','eslam@gmail.com')->first();

        if(!$user){
            User::Create([
'name'=>'eslam',
'email'=>'eslam@gmail.com',
'role'=>'admin',
'password'=>Hash::make('password')
            ]);
        }
    }
}
