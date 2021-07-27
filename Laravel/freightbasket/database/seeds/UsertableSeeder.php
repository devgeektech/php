<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class UsertableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            User::create([
			      'name' => 'Hardik',
			      'email' => 'admin@gmail.com',
            'role_id'=>'1',
            'avatar'=>'uploads/profiles/default-user-photo.jpg',
            'password' => bcrypt('demo@123'),
            'phone' =>'123456789',
            'address'=>'abc',
            'about'=>'abc'
		]);

      User::create([

            'name' => 'ankit',
			      'email' => 'user@gmail.com',
            'role_id'=>'2',
            'avatar'=>'uploads/profiles/default-user-photo.jpg',
            'password' => bcrypt('demo@123'),
            'phone' =>'123456789',
            'address'=>'abc',
            'about'=>'abc'
		]);
    }
}
