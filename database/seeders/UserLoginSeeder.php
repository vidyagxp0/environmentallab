<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user  = new User();
        $user->name = "Amit Guru";
        $user->email = "amit.guru@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Shaleen Mishra";
        $user->email = "shaleen.mishra@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "Vikas Prajapati";
        $user->email = "vikas.prajapati@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        $user  = new User();
        $user->name = "Anshul Patel";
        $user->email = "anshul.patel@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "Amit Patel";
        $user->email = "amit.patel@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "Madhulika Mishra";
        $user->email = "madhulika.mishra@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "Jin Kim";
        $user->email = "jin.kim@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Akash Asthana";
        $user->email = "akash.asthana@connexodemo.com";
        $user->password = Hash::make('1234567890');
        $user->departmentid = 1;
        $user->role = 8;
        $user->save();
    }
}
