<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Category;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
         Admin::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@bloggi.test',
            'mobile' => '966509321765',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('123123123'),
            'status' => 1,

        ]);
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@bloggi.test',
            'mobile' => '966509321765',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('123123123'),
            'status' => 1,

        ]);
        for($i=0; $i<10; $i++){
            User::create([
                'name' => $faker->name,
                'username' => $faker->userName,
                'email' => $faker->email,
                'mobile' => '9665'.random_int(10000000, 99999999),
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('123456789'),
                'status' => 1,

            ]);
        }
    }
}