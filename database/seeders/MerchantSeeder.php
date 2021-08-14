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

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = new Merchant();
        $merchant->nick = 'Huawei';
        $merchant->name = 'Huawei';
        $merchant->resources = "{\"url_image\":\"https://revistasumma.com/wp-content/uploads/2019/07/Screen-Shot-2019-07-08-at-08.12.39-1080x675.png\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
		
		
		$merchant = new Merchant();
        $merchant->nick = 'CRC';
        $merchant->name = 'Comisión nacional de regulación';
        $merchant->resources = "{\"url_image\":\"https://www.crcom.gov.co/uploads/images/images/avatar-.jpg\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
        
        
		
		$merchant = new Merchant();
        $merchant->nick = 'Wom';
        $merchant->name = 'Wom';
        $merchant->resources = "{\"url_image\":\"https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/WOM_logo.png/599px-WOM_logo.png\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save(); 
		
		
		
		//** Fair two*//
		$merchant = new Merchant();
        $merchant->nick = 'Cerbero';
        $merchant->name = 'Daniel Pérez Navarro';
        $merchant->resources = "{\"url_image\":\"https://www.libros-prohibidos.com/wp-content/uploads/2017/10/los-principes-de-madera-150x150.jpg\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
		
		
		$merchant = new Merchant();
        $merchant->nick = 'Rayo Verde';
        $merchant->name = 'David Monteagudo';
        $merchant->resources = "{\"url_image\":\" https://www.libros-prohibidos.com/wp-content/uploads/2017/11/cronicas-del-amacrana-Libros-Prohibidos-300x451.jpg\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
        
        
		
		$merchant = new Merchant();
        $merchant->nick = 'Ngũgĩ wa Thiong’o';
        $merchant->name = 'Ngũgĩ wa Thiong’o';
        $merchant->resources = "{\"url_image\":\"https://www.libros-prohibidos.com/wp-content/uploads/2017/09/no-llores-pequeño-300x451.jpg\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save(); 

    }
}
