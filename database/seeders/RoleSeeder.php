<?php

namespace Database\Seeders;


use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = new Role();
        $role->name = 'super_administrador';
        $role->description = 'Usuarios con permisos de creación/modificación de todas las feria';
        $role->save();

        $role = new Role();
        $role->name = 'administrador_feria';
        $role->description = 'Usuario con permiso de creacion/modificacion de una feria en particular';
        $role->save();

        $role = new Role();
        $role->name = 'administrador_stand';
        $role->description = 'Usuario con permiso de modificacion de elementos de un stand';
        $role->save();

        $role = new Role();
        $role->name = 'cliente_final';
        $role->description = 'Usuario que puede comprar y registrarse como invitado dentro de una feria';
        $role->save();

        $role = new Role();
        $role->name = 'comercio';
        $role->description = 'Usuario que puede visualizar las metricas de un stand en particular';
        $role->save();

        $role = new Role();
        $role->name = 'conferencista';
        $role->description = 'Usuario que puede ser anfitrion dentro de un evento';
        $role->save();
    }
}
