<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
         $this->call(RolesTableSeeder::class);
         $this->call(DataTypesTableSeeder::class);
         $this->call(PermissionRoleTableSeeder::class);
         $this->call(PermissionsTableSeeder::class);
         $this->call(SettingsTableSeeder::class);
         $this->call(VoyagerDatabaseSeeder::class);
         $this->call(UsertableSeeder::class);
         $this->call(UserRolesSeeder::class);
        
    }
}
