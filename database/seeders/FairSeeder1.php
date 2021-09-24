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

class FairSeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //*** Fair 1 **//
        $fair = new Fair();
        $fair->name = 'sublimacion';
        $fair->description = 'Sublimación';
        $fair->halls_number = 2;
        $fair->init_date = '2021-12-24';
        $fair->end_date = '2021-12-31';
        $fair->resources = '{"scenes":[]}';
        $fair->location = '{}';
        $fair->social_media = '{"icon":"assets/icon/icon.png","iconNight":"assets/icon/icon-black.png"}';
        $fair->save();
    }
}
