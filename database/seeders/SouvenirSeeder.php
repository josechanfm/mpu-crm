<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Souvenir;
use App\Models\SouvenirUser;

class SouvenirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Souvenir::create([
            "name"=>"USB Thumb drive",
            "description"=>"USB thumb drive with MPU logo",
            "thumbnail"=>"/images/souvenirs/Phone_Strap.jpg",
            "stock"=>500,
            "price"=>100,
            "quota"=>3
        ]);
        Souvenir::create([
            "name"=>"Pin",
            "description"=>"MPU logo pins",
            "thumbnail"=>"/images/souvenirs/Bear.jpg",
            "stock"=>500,
            "price"=>100,
            "quota"=>3
        ]);
        Souvenir::create([
            "name"=>"T-Shirt",
            "description"=>"MPU logo T-shirt",
            "thumbnail"=>"/images/souvenirs/Cardholder_Lanyard.jpg",
            "stock"=>500,
            "price"=>100,
            "quota"=>3
        ]);

        $souvenirUsers=[
            ["netid"=>"p2000012","email"=>"p2000010@mpu.edu.mo","phone"=>"64562101"],
            ["netid"=>"p2000029","email"=>"p2000020@mpu.edu.mo","phone"=>"64562102"],
            ["netid"=>"p2000031","email"=>"p2000030@mpu.edu.mo","phone"=>"64562103"],
            ["netid"=>"p2000040","email"=>"p2000040@mpu.edu.mo","phone"=>"64562104"],
            ["netid"=>"p2000050","email"=>"p2000050@mpu.edu.mo","phone"=>"64562105"],
            ["netid"=>"p2000060","email"=>"p2000060@mpu.edu.mo","phone"=>"64562106"],
            ["netid"=>"p2000070","email"=>"p2000070@mpu.edu.mo","phone"=>"64562107"],
            ["netid"=>"p2000080","email"=>"p2000080@mpu.edu.mo","phone"=>"64562108"],
            ["netid"=>"p2000090","email"=>"p2000090@mpu.edu.mo","phone"=>"64562109"],
        ];
        foreach ($souvenirUsers as $user) {
            SouvenirUser::create($user);
        }
    }
}

