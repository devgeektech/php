<?php

use Illuminate\Database\Seeder;
//use Illuminate\Database\Seeder\AdvertismentsTableSeeder;
//use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run__()
    {
        //  $this->call(UsersTableSeeder::class);
          DB::table('advertisments')->insert([
            'name' => str_random(10),
            'image' => 'public/images/advertisments/e08adb1f589f0e741b86a5cd80dac3f6.jpg',
            'category_id' => 1 
        ]);  
          
        //  $this->call([
        //     AdvertismentsTableSeeder::class,
        // ]);
    }
    
    public function run()
    {
       // $this->call(CountriesTableSeeder::class);
       //$this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        
    }
    
}
