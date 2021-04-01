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

class SpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $speaker = new Speaker();
        $speaker->user_id = 2;
        $speaker->description = '25 años de experiencia en el mundo de la marroquinería y del calzado';
        $speaker->title = "Confeccionista";
        $speaker->resources = '{\"url_image\":\"https://image.freepik.com/vector-gratis/perfil-femenino-silueta_23-2147522231.jpg\"}';
        $speaker->save();
        
        $speaker = new Speaker();
        $speaker->user_id = 3;
        $speaker->description = 'La mejor expositora de bolsos con exportación desde colombia, Tendencias 2021';
        $speaker->title = "Compositora";
        $speaker->resources = '{\"url_image\":\"https://i.pinimg.com/originals/40/32/e0/4032e0031e2e95989f1e76fe3d4f57b7.jpg\"}';
        $speaker->save();
		
        $speaker = new Speaker();
        $speaker->user_id = 4;
        $speaker->description = 'Talent Manager CO';
        $speaker->title = "Talent Manager CO";
        $speaker->resources = '{\"url_image\":\"https://images.vexels.com/media/users/3/140749/isolated/preview/4fb58265f9e1ad8d8dd7c35f06fa58d6-avatar-de-perfil-masculino-1-by-vexels.png\"';
        $speaker->save();

    }
}
