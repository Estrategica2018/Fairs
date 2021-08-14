<?php

namespace Database\Seeders;

use App\Models\Fair;
use App\Models\StandTyp;
use App\Models\Pavilion;
use App\Models\Speaker;
use App\Models\Merchant;
use App\Models\Stand;
use App\Models\Agendas;
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
        $fair->name = 'feriatecnologica2021';
        $fair->description = 'XI Feria Tecnológica 2021';
        $fair->halls_number = 1;
        $fair->init_date = '2021-12-24';
        $fair->end_date = '2021-12-31';
        $fair->resources = '{"scenes":[]}';
		$fair->location = '{}';
        $fair->social_media = '{"icon":"assets/icon/icon.png","iconNight":"assets/icon/icon-black.png"}';
        $fair->save();
		
        $fair = new Fair();
        $fair->name = 'libromania';
        $fair->description = 'I Feria Independiente del libro 2021';
        $fair->halls_number = 1;
        $fair->init_date = '2021-09-02';
        $fair->end_date = '2021-09-17';
        $fair->resources = '{"scenes":[]}';
		$fair->location = '{}';
        $fair->social_media = '{"icon":"assets/icon/icon.png","iconNight":"assets/icon/icon-black.png"}';
        $fair->save();
		
		
    }
}
