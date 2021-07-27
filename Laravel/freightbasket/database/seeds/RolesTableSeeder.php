<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' =>'Administrator',
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('voyager::seeders.roles.user'),
            ])->save();
        }
         $role = Role::firstOrNew(['name' => 'Producer']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Producer',
            ])->save();
        }
        $role = Role::firstOrNew(['name' => 'Service Provider']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Service Provider',
            ])->save();
        }
        $role = Role::firstOrNew(['name' => 'Freelancer']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Freelancer',
            ])->save();
        }
        
         $role = Role::firstOrNew(['name' => 'Company_staff']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => 'Company Staff',
            ])->save();
        }
        
    }
}
