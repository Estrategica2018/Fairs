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
        $fair->resources = '{"iconNight":"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615656994/feria1/icon-black_mkoa7r.png",
							 "icon":"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615656994/feria1/icon_hzvtet.png",
						     "url_image":"https://res.cloudinary.com/dfxkgtknu/image/upload/v1611542204/feria1/feria_Easy-Resize.com_j9svzu.jpg",
                             "banners":[{ 
							 "image_url":"assets/images_fairs/fair1/banner-480x400.png",
							 "rotation":{"_x":-0.060000000000000005,"_y":0.7300000000000012,"_z":0,"_order":"XYZ"},"scale":{"x":0.4299999999999988,"y":0.7500000000000002,"z":1},"position":{"x":2.349999999999995,"y":-0.04,"z":0},
							 "callback": { "pavilion_id": "1", "type": "pavilion" }
							 }]}';
		$fair->location = '{}';
        $fair->social_media = '{}';
        $fair->save();
        
        $pavilion = new Pavilion();
        $pavilion->name = 'Pabellón Principal';
        $pavilion->description = '';
        $pavilion->fair_id = $fair->id;
		$scene = "{\"type\":\"stands\",\"resources\":{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1611542206/feria1/pabellon2_Easy-Resize.com_i9o9nv.jpg\"}}";
		$scene1 = "{\"type\":\"room\",\"resources\":{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1611542378/feria1/auditorio1-background_Easy-Resize.com_m1sazv.jpg\"}}";
        $pavilion->resources = "{ \"_defaultWidth\": 1076,\"_defaultHeight\": 605,
		\"video\":\"assets/images_fairs/fair1/ezgif.com-gif-maker.mp4\",
		\"scenes\":[".$scene.",".$scene1." ] , 
        \"url_image\":\"assets/images_fairs/fair1/final_6052b424027e13005b09bf06_901908.jpg\",
		\"banners\":[   {      \"image_url\":\"assets/images_fairs/fair1/banner-680x400.png\",      \"rotation\":{         \"_x\":-0.10999999999999999,         \"_y\":0.9100000000000014,         \"_z\":-0.01,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.659999999999999,         \"y\":1.690000000000001,         \"z\":1      },      \"position\":{         \"x\":-2.5099999999999896,         \"y\":1.9400000000000013,         \"z\":-0.15      }   },      {      \"image_url\":\"assets/images_fairs/fair1/banner-1200x180.png\",      \"rotation\":{         \"_x\":0.02000000000000003,         \"_y\":0.16000000000000075,         \"_z\":-0.04000000000000001,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.579999999999999,         \"y\":0.9100000000000004,         \"z\":1      },      \"position\":{         \"x\":-3.2299999999999742,         \"y\":-2.3699999999999934,         \"z\":-0.7800000000000005      }   },   {      \"image_url\":\"assets/images_fairs/fair1/banner-1366x768.png\",      \"rotation\":{         \"_x\":-0.06999999999999992,         \"_y\":0.620000000000001,         \"_z\":-0.06999999999999992,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.1799999999999986,         \"y\":0.23999999999999977,         \"z\":1.3800000000000003      },      \"position\":{         \"x\":-0.9499999999999995,         \"y\":0.3300000000000001,         \"z\":5.919999999999918      }   },   {      \"image_url\":\"assets/images_fairs/fair1/pavillion1-480x400.png\",      \"rotation\":{         \"_x\":0,         \"_y\":0,         \"_z\":0,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.36999999999999944,         \"y\":1.9700000000000009,         \"z\":1      },      \"position\":{         \"x\":4.109999999999957,         \"y\":-1.6600000000000013,         \"z\":0      }   },   {      \"image_url\":\"assets/images_fairs/fair1/pabellon-back-480x400.png\",      \"rotation\":{         \"_x\":0,         \"_y\":0,         \"_z\":-0.010000000000000004,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.059999999999999255,         \"y\":0.15999999999999925,         \"z\":1.2300000000000002      },      \"position\":{         \"x\":-1.3100000000000007,         \"y\":0.12000000000000006,         \"z\":5.889999999999919      },      \"callback\":{         \"fair_name\":\"feriaganadera2021\",         \"type\":\"fair\"      }   },   {      \"image_url\":\"assets/images_fairs/fair1/menu-pabellon2-520x230.png\",      \"rotation\":{         \"_x\":0,         \"_y\":0,         \"_z\":0,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.25999999999999934,         \"y\":0.5699999999999996,         \"z\":1      },      \"position\":{         \"x\":3.7199999999999647,         \"y\":2.289999999999995,         \"z\":-0.03      },      \"callback\":{         \"pavilion_id\":\"2\",         \"type\":\"pavilion\"      }   },   {      \"image_url\":\"assets/images_fairs/fair1/menu-auditorio1-520x230.png\",      \"rotation\":{         \"_x\":0,         \"_y\":0,         \"_z\":0,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.25999999999999934,         \"y\":0.5699999999999996,         \"z\":1      },      \"position\":{         \"x\":3.709999999999965,         \"y\":1.640000000000001,         \"z\":0.010000000000000004      },      \"callback\":{         \"room_id\":\"1\",         \"type\":\"room\"      }   },   {      \"image_url\":\"assets/images_fairs/fair1/menu-speakers-520x230.png\",      \"rotation\":{         \"_x\":0,         \"_y\":0,         \"_z\":0,         \"_order\":\"XYZ\"      },      \"scale\":{         \"x\":0.25999999999999934,         \"y\":0.5699999999999996,         \"z\":1      },      \"position\":{         \"x\":3.749999999999964,         \"y\":1.0000000000000004,         \"z\":-0.03      },      \"callback\":{         \"fair_name\":\"feriaganadera2021\",         \"type\":\"speakers\"      }   }]}";
        $pavilion->save();
        
                
        $merchant = new Merchant();
        $merchant->nick = 'Huawei';
        $merchant->name = 'Huawei';
        $merchant->resources = "{\"url_image\":\"https://revistasumma.com/wp-content/uploads/2019/07/Screen-Shot-2019-07-08-at-08.12.39-1080x675.png\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
		
		
		$standType = new StandTyp();
		$standType->stand_services = 'Tipo 1';
		$standType->save();
		
        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = $standType->id;
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1616472216/IMG_20190628_121642_zii7uj.jpg\",
		\"banners\":[   ]}";
        $stand->save();        
		

		$merchant = new Merchant();
        $merchant->nick = 'CRC';
        $merchant->name = 'Comisión nacional de regulación';
        $merchant->resources = "{\"url_image\":\"https://www.crcom.gov.co/uploads/images/images/avatar-.jpg\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
        
        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = '1';
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1616472269/RevistaCRC_20aniversario_atiwmv.png\",
		\"banners\":[]}";
        $stand->save();        
		
		
		$merchant = new Merchant();
        $merchant->nick = 'Wom';
        $merchant->name = 'Wom';
        $merchant->resources = "{\"url_image\":\"https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/WOM_logo.png/599px-WOM_logo.png\"}";
        $merchant->social_media = "{}";
        $merchant->location = "{}";
        $merchant->save();        
        
        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = '1';
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615756379/feria1/wom_ejhcna.jpg\",
		\"banners\":[]}";
        $stand->save();      
		
		
		
		
		$pavilion = new Pavilion();
        $pavilion->name = 'Pabellón WOM';
        $pavilion->description = '';
        $pavilion->fair_id = $fair->id;
		$scene = "{\"type\":\"room\",\"resources\":{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1611542378/feria1/auditorio1-background_Easy-Resize.com_m1sazv.jpg\"}}";
		$pavilion->resources = "{ \"scenes\":[".$scene." ] , 
		\"_defaultWidth\": 1076,\"_defaultHeight\": 605,
		\"video\":\"https://res.cloudinary.com/dfxkgtknu/video/upload/v1617210963/demoVideo/Test_jskryk.mp4\",
		\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1617210945/demoVideo/Test_0_00_05_00_dnduxo.png\",
		\"scenes\":[".$scene." ], 
		\"banners\":[] }";
        $pavilion->save();
        
        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = '1';
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615756379/feria1/wom_ejhcna.jpg\",
		\"banners\":[]}";
        $stand->save();        

        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = '1';
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615756379/feria1/wom_ejhcna.jpg\",
		\"banners\":[]}";
        $stand->save();        
		
        $stand = new Stand();
        $stand->merchant_id = $merchant->id;
        $stand->pavilion_id = $pavilion->id;
        $stand->stand_type_id = '1';
        $stand->resources = "{\"url_image\":\"https://res.cloudinary.com/dfxkgtknu/image/upload/v1615756379/feria1/wom_ejhcna.jpg\",
		\"banners\":[]}";
        $stand->save(); 
		



    }
}
