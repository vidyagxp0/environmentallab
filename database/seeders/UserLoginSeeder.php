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
        $user->name = "Esra'a Hyasat";
        $user->email = "esraa.hyasat@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        
        $user  = new User();
        $user->name = "Hussam Jariri";
        $user->email = "hussam.jariri@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Rawan Abu Al- Naja";
        $user->email = "Results.abu@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        
        $user  = new User();
        $user->name = "Sami Samara";
        $user->email = "Sami.samara@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Maisa Safi";
        $user->email = "maisa.safi@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Mohammad Eid";
        $user->email = "mohammad.edi@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Sanaa Taha";
        $user->email = "sanaa.taha@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Rahaf Shawabkih";
        $user->email = "rahaf.shawabhih@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();
        
        $user  = new User();
        $user->name = "Rawand Mubark";
        $user->email = "rawand.mubark@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

               

      

        $user  = new User();
        $user->name = "Hanyia Shawhanieh ";
        $user->email = "hanyia.shwhanieh@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();


    
        $user  = new User();
        $user->name = "Sukina Bustanji";
        $user->email = "Sukina.bustanji@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();


        

        
         $user  = new User();
        $user->name = "Ahmad Jabali";
        $user->email = "ahmad.jabali@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();


        
        $user  = new User();
        $user->name = "Ahmad Hamdallah";
        $user->email = "ahmad.hamdallah@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Fadi Al-Fuqaha";
        $user->email = "fadi.aifuqaha@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Sondos Samara";
        $user->email = "sondos.samara@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Yasmeen Abu Zahra";
        $user->email = "yasmeen.abhuzahra@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();

        $user  = new User();
        $user->name = "Dana Abu Shanab";
        $user->email = "dana.abushanab@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();




        $user  = new User();
        $user->name = "Hussam Jariri";
        $user->email = "hussam.jariri@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        

        $user  = new User();
        $user->name = "Haneen Samara";
        $user->email = "haneen.samara@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();


        $user  = new User();
        $user->name = "Khawla Masoud ";
        $user->email = "khawla.masoud@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 3;
        $user->save();



       
        // $user  = new User();
        // $user->name = "Amit Guru";
        // $user->email = "amit.guru@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "Sondos";
        // $user->email = "Sondos@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1fp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2fp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3fp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4fp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5fp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();


        $user  = new User();
        $user->name = "environmentallab hod1";
        $user->email = "environmentallab.hod1@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();


        $user  = new User();
        $user->name = "environmentallab hod2";
        $user->email = "environmentallab.hod2@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();


        $user  = new User();
        $user->name = "environmentallab hod3";
        $user->email = "environmentallab.hod3@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 4;
        $user->save();



        // $user  = new User();
        // $user->name = "Shaleen Mishra";
        // $user->email = "shaleen.mishra@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1hod@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2hod@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3hod@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4hod@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5hod@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 4;
        // $user->save();


        // $user  = new User();
        // $user->name = "Vikas Prajapati";
        // $user->email = "vikas.prajapati@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1app@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();


        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2app@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();


        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3app@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();


        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4app@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();


        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5app@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 1;
        // $user->save();


        $user  = new User();
        $user->name = "environmentallab cft";
        $user->email = "environmentallab.cft1@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();


        $user  = new User();
        $user->name = "environmentallab cft";
        $user->email = "environmentallab.cft2@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();

        $user  = new User();
        $user->name = "environmentallab cft";
        $user->email = "environmentallab.cft3@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 5;
        $user->save();






        // $user  = new User();
        // $user->name = "Anshul Patel";
        // $user->email = "anshul.patel@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1cft@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2cft@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3cft@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4cft@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5cft@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 5;
        // $user->save();



        $user  = new User();
        $user->name = "environmentallab approver1";
        $user->email = "environmentallab.approver1@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        $user  = new User();
        $user->name = "environmentallab approver2";
        $user->email = "environmentallab.approver2@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();

        $user  = new User();
        $user->name = "environmentallab approver3";
        $user->email = "environmentallab.approver3@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 1;
        $user->save();




        $user  = new User();
        $user->name = "environmentallab Reviewer1";
        $user->email = "environmentallab.Reviewer1@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();

        $user  = new User();
        $user->name = "environmentallab Reviewer2";
        $user->email = "environmentallab.Reviewer2@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();


        $user  = new User();
        $user->name = "environmentallab Reviewer3";
        $user->email = "environmentallab.Reviewer3@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 2;
        $user->save();


        // $user  = new User();
        // $user->name = "Amit Patel";
        // $user->email = "amit.patel@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1rep@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2rep@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3rep@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4rep@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5rep@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 2;
        // $user->save();


        $user  = new User();
        $user->name = "environmentallab training coordinator1";
        $user->email = "environmentallab.trainingcoordinator1@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();



        $user  = new User();
        $user->name = "environmentallab training coordinator2";
        $user->email = "environmentallab.trainingcoordinator2@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();

        $user  = new User();
        $user->name = "environmentallab training coordinator3";
        $user->email = "environmentallab.trainingcoordinator3@mydemosoftware.com";
        $user->password = Hash::make('Dms@123');
        $user->departmentid = 1;
        $user->role = 6;
        $user->save();



        // $user  = new User();
        // $user->name = "Madhulika Mishra";
        // $user->email = "madhulika.mishra@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 1";
        // $user->email = "user1tp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 2";
        // $user->email = "user2tp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 3";
        // $user->email = "user3tp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 4";
        // $user->email = "user4tp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "User 5";
        // $user->email = "user5tp@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 6;
        // $user->save();

        // $user  = new User();
        // $user->name = "Jin Kim";
        // $user->email = "jin.kim@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 3;
        // $user->save();

        // $user  = new User();
        // $user->name = "Akash Asthana";
        // $user->email = "akash.asthana@mydemosoftware.com";
        // $user->password = Hash::make('Dms@123');
        // $user->departmentid = 1;
        // $user->role = 8;
        // $user->save();
    }
}
