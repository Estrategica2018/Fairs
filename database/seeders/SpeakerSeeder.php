<?php

namespace Database\Seeders;

use App\Models\Fair;
use App\Models\StandTyp;
use App\Models\Pavilion;
use App\Models\Speaker;
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
        $speaker->resources = '{"url_image":"https://image.freepik.com/vector-gratis/perfil-femenino-silueta_23-2147522231.jpg"}';
        $speaker->save();
        
        $speaker = new Speaker();
        $speaker->user_id = 3;
        $speaker->description = 'La mejor expositora de bolsos con exportación desde colombia, Tendencias 2021';
        $speaker->title = "Compositora";
        $speaker->resources = '{"url_image":"https://i.pinimg.com/originals/40/32/e0/4032e0031e2e95989f1e76fe3d4f57b7.jpg"}';
        $speaker->save();
		
        $speaker = new Speaker();
        $speaker->user_id = 4;
        $speaker->description = 'Talent Manager CO';
        $speaker->title = "Talent Manager CO";
        $speaker->resources = '{"url_image":"https://images.vexels.com/media/users/3/140749/isolated/preview/4fb58265f9e1ad8d8dd7c35f06fa58d6-avatar-de-perfil-masculino-1-by-vexels.png"}';
        $speaker->save();
		
		
		
		////***** Fair Two ////
        $speaker = new Speaker();
        $speaker->user_id = 5;
        $speaker->description = 'Obra ganadora del Premio Guillermo de Baskerville 2017 a la mejor novela corta';
        $speaker->title = "Escritora";
        $speaker->resources = '{"url_image":"https://www.libros-prohibidos.com/wp-content/uploads/2017/06/los-principes-de-madera-Libros-Prohibidos-300x451.jpg"}';
        $speaker->save();
        
        $speaker = new Speaker();
        $speaker->user_id = 6;
        $speaker->description = 'David Monteagudo (Vivero, Lugo, 1962) es un escritor español. En 2012, este relato fue llevado al cine con el mismo título, dirigida por Jorge Torregrossa. Desde entonces ha publicado siete nuevos libros. Ha sido traducido a seis idiomas';
        $speaker->title = "Escritor";
        $speaker->resources = '{"url_image":"https://www.libros-prohibidos.com/wp-content/uploads/2017/11/cronicas-del-amacrana-Libros-Prohibidos-300x451.jpg"}';
        $speaker->save();
		
        $speaker = new Speaker();
        $speaker->user_id = 7;
        $speaker->description = 'Ngũgĩ wa Thiong\'o Kenia, 5 de enero de 19381​) es un escritor de Kenia. Ha escrito varias novelas, ensayos y cuentos; fundado el periódico en kikuyu Mutiiri y colaborado en el departamento de traducción e interpretación de la Universidad de California en Irvine. ';
        $speaker->title = "Escritor";
        $speaker->resources = '{"url_image":"http://t3.gstatic.com/licensed-image?q=tbn:ANd9GcSFHWyAhqMW1D11YySc-m2fY45z4nRtiiShSKCiMsWPdgNWd_0Hg-2KiVj7FVzy"}';
        $speaker->save();



    }
}
