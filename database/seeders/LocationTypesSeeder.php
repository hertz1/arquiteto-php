<?php

namespace Database\Seeders;

use App\Models\LocationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('location_types')->insert([
            ['id' => LocationType::COUNTRY    , 'name' => 'Country'],
            ['id' => LocationType::STATE      , 'name' => 'State'],
            ['id' => LocationType::CITY       , 'name' => 'City'],
            ['id' => LocationType::DISTRICT   , 'name' => 'District'],
            ['id' => LocationType::POSTAL_CODE, 'name' => 'Postal Code']
        ]);
    }
}
