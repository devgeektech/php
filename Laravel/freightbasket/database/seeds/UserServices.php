<?php

use Illuminate\Database\Seeder;

class UserServices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('services')->insert([
            'role_id' => 4,
            'service_name' => 'custom brokers',
            'service_slug' => '',
        ]);
    }
}
