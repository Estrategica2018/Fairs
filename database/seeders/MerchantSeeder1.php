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

class MerchantSeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = new Merchant();
        $merchant->nick = 'Estratégica comunicaciones';
        $merchant->name = 'Estratégica comunicaciones';
        $merchant->resources = "{\"url_image\":\"https://www.estrategicacomunicaciones.com/wp-content/uploads/2016/01/logo-estrategica-159x60.png\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
		 

    }
}
