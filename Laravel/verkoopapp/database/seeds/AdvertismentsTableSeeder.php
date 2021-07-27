<?php

use Illuminate\Database\Seeder;

class AdvertismentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Advertisments::class, 5)->create();
    }
}
