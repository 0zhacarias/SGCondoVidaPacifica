<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $user = User::create([
            'name' => "Renata de sousa",
            'password' => Hash::make('sigc2025'),
            'email' => 'renata@gmail.com',
            'telefone' => 970326589,
            'username' => 'renata.sousa',
        ]);
        $role = Role::findByName('Administrador');
        $role->givePermissionTo(Permission::all());
        $user->assignRole('Administrador');
        DB::table('pessoas')->insert([
            'nome_pessoa' => 'Renata de Sousa',
            'sobre_nome_pessoa' => 'Sousa',
            'numero_identificacao' => '006532147LA520',
            'email_pessoa' => 'renata@gmail.com',
            'telefone_pessoa' => 'Renata de Sousa',
            'user_id' => $user['id'],
            'estado_civil_id' => 2,
            'tipo_documento_identificacao_id' => 2,
            'genero_id' => 2,
            'funcao_id'=>1,
        ]);
        DB::commit();
        DB::rollback();
    }
}
