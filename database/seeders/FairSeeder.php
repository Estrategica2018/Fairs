<?php

namespace Database\Seeders;

use App\Models\Fair;
use App\Models\StandTyp;
use App\Models\Pavilion;
use App\Models\Speaker;
use App\Models\RoleUserFair;
use App\Models\Role;
use App\Models\User;
use App\Models\Agendas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $rol = new Role();
        $rol->id = 1;
        $rol->name = 'super_administrador';
        $rol->description = 'Usuarios con permisos de creación/modificación de todas las feria';
        $rol->save();

        $rol = new Role();
        $rol->id = 2;
        $rol->name = 'usuario administrador';
        $rol->description = 'Usuario con permiso de creacion/modificacion de una feria en particular';
        $rol->save();

        $rol = new Role();
        $rol->id = 3;
        $rol->name = 'administrador stand comercial';
        $rol->description = 'Usuario con permiso de modificacion de elementos de un stand';
        $rol->save();

        $rol = new Role();
        $rol->id = 4;
        $rol->name = 'cliente final';
        $rol->description = 'Usuario que puede comprar y registrarse como invitado dentro de una feria';
        $rol->save(); 

        $rol = new Role();
        $rol->id = 5;
        $rol->name = 'comercio';
        $rol->description = 'Usuario que puede visualizar las metricas de un stand en particular';
        $rol->save();

        $rol = new Role();
        $rol->id = 6;
        $rol->name = 'conferencista';
        $rol->description = 'Usuario que puede ser anfitrion dentro de un evento';
        $rol->save();



        //*** Fair 1 **//
        $fair = new Fair();
        $fair->name = 'sublimaciones';
        $fair->description = 'Sublimación';
        $fair->halls_number = 2;
        $fair->init_date = '2021-12-24';
        $fair->end_date = '2021-12-31';
        $fair->resources =  '{"scenes":[{"banners":[],"container":{"w":1366,"h":960},"show":true,"menuIcon":"map-outline","title":"Escena Principal"}]}';
        $fair->location = '{}';
        $fair->social_media = '{"icon":"assets/icon/icon.png","iconNight":"assets/icon/icon-black.png"}';
        $fair->save();
        

        $user = new User();
        $user->user_name = 'Cristian01';
        $user->name = 'Cristian';
        $user->last_name = 'Jojoa';
        $user->email = 'cristianjojoa01@gmail.com';
        $user->url_image = 'image2';
        $user->contact = 'contact2';
        $user->password = Hash::make('123');
        $user->save(); 

		$user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = 1;
        $user_rol_fair->fair_id = $fair->id;
        $user_rol_fair->save();

        $user = new User();
        $user->user_name = 'DavidCamacho';
        $user->name = 'David';
        $user->last_name = 'Camacho';
        $user->email = 'davithc01@gmail.com';
        $user->url_image = '';
        $user->contact = '{}';
        $user->password = Hash::make('12345678');
        $user->save();
		
		$user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = 1;
        $user_rol_fair->fair_id = $fair->id;
        $user_rol_fair->save();

        $user = new User();
        $user->user_name = 'Jessy';
        $user->name = 'Jessy';
        $user->last_name = 'Pinzon';
        $user->email = 'jesspinzong@gmail.com';
        $user->url_image = '';
        $user->contact = '{}';
        $user->password = Hash::make('12345678');
        $user->save();
		
		$user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = 1;
        $user_rol_fair->fair_id = $fair->id;
        $user_rol_fair->save();

    }
}
