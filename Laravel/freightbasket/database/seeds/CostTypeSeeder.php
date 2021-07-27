<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_cost_type')->insert([
            'cost_type' => "ocean freight",
            'services' => "land",
        ]);
    }
}
