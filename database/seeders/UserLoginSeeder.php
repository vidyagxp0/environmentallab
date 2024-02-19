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
        $user->email = "amit.guru@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Sondos";
        $user->email = "Sondos@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 1";
        $user->email = "user1fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 2";
        $user->email = "user2fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 3";
        $user->email = "user3fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 4";
        $user->email = "user4fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "User 5";
        $user->email = "user5fp@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Shaleen Mishra";
        $user->email = "shaleen.mishra@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();

        $user  = new User();
        $user->name = "Vikas Prajapati";
        $user->email = "vikas.prajapati@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        $user  = new User();
        $user->name = "Anshul Patel";
        $user->email = "anshul.patel@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "Amit Patel";
        $user->email = "amit.patel@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "Madhulika Mishra";
        $user->email = "madhulika.mishra@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "Jin Kim";
        $user->email = "jin.kim@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Akash Asthana";
        $user->email = "akash.asthana@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 8;
        $user->save();
    }
}
