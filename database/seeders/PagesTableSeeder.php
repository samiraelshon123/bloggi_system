<?php

namespace Database\Seeders;
use App\Models\Page;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Page::create([
            'title' => 'About Us',
            'descreption' => $faker->paragraph(),
            'status' => 1,
            'comment_able' => 0,
            'post_type' => 'page',
            'user_id' => 1,
            'category_id' => 1,
        ]);

        Page::create([
            'title' => 'Our Vesion',
            'descreption' => $faker->paragraph(),
            'status' => 1,
            'comment_able' => 0,
            'post_type' => 'page',
            'user_id' => 1,
            'category_id' => 1,
        ]);
    }
}
