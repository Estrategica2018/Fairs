<?php

namespace Database\Seeders;

use App\Models\Fair;
use Illuminate\Database\Seeder;

class FairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fair = new Fair();
		$fair->name = 'FeriaGanadera2021';
        $fair->description = 'XI Feria Ganadera 2021';
		$fair->halls_number = 1;
		$fair->init_date = '2021-12-24';
		$fair->end_date = '2021-12-31';
		$fair->resources = '{}';
		$fair->location = '{}';
		$fair->social_media = '{}';
        $fair->save();
    }
}
