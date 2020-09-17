<?php

use Illuminate\Database\Seeder;

class UsersTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 40)->create()->each(function($user){
            //METODO SAV TRABALHA COM OBJETOS E CREATE COM ARRAY
            //PARA CADA USUARIO ELE CRIA UMA LOJA
            $user->store()->save(factory(\App\Store::class)->make());
        });
    }
}
