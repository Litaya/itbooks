<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1, 20) as $index){
            DB::table('conference')->insert([
                'name' => $faker->sentence($nb=4),
                'time' => $faker->dateTimeBetween($startDate='+1 days', $endDate='+5 days'),
                'location' => $faker->address,
                'host' => $faker->company,
                'detail_url' => $faker->url,
                'description' => $faker->paragraph
            ]);
        }
    }
}
