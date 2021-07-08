<?php

namespace Database\Seeders;

use App\Models\StandType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StandTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$stand = new StandType();
        $stand->stand_services = '{"name":"Local simple, fondo, videos, 4 banners", "resources":[]}';
        $stand->save();
		
		$stand = new StandType();
        $stand->stand_services = '{"name":"Local simple, fondo, videos, 10 banners, pantalla video", "resources":[]}';
        $stand->save();
		
		$stand = new StandType();
        $stand->stand_services = '{"name":"Local simple, fondo, videos, 12 banners, 3 aditorios", "resources":[]}';
        $stand->save();
		
    }
}
