<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Http\Request;


class FuncoesPermissoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    protected function serializeDate(DateTimeInterface $date){
        return $date->format('d M Y');
    }

    public function index()
    {
        $data['roles'] = Role::with('permissions')->get();
        $data['permissions'] = Permission::with('roles')->get();
        return Inertia::render('User/FuncaoPermissao',$data);
    }
    public function funcao_permissao()
    {
        $data['roles'] = Role::with('permissions')->get();
        $data['permissions'] = Permission::with('roles')->get();
        return Inertia::render('User/SincronizarRolesPermissoes',$data);
    }
    // selecionar as funcoes marcadas
    public function getRoles(Request $request)
    {
        $role_id = $request->get('role_id');

        if ($role_id) {
            $roles = Role::with('permissions')->where('id', $role_id)->first();
        }

        return Response()->json($roles);
    }

    public function concederFuncoes(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->syncRoles($request->get('user_roles'));
        // $user->assignRole($request->get('user_roles'));
        // dd($user);
        return redirect()->back()->with('success', 'Funções sincronizadas com sucesso!...');
    }
    public function concederPermissoes(Request $request, $user_id)
    {
        $user = User::find($user_id);
      // dd($user,$request->get('user_permissions'));
    //    dd($request->get('user_permissions'));
        $user->syncPermissions($request->get('user_permissions'));

        return redirect()->back()->with('success', 'Permissões sincronizadas com sucesso!...');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funcoes_Permissoes  $funcoes_Permissoes
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Funcoes_Permissoes  $funcoes_Permissoes
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funcoes_Permissoes  $funcoes_Permissoes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

   
        if ($id) {
            $role = Role::find($id);

            $role->syncPermissions($request->get('role_permissions'));

            return redirect()->back()->with('success', 'Permissão associada com sucesso!...');
        } else {
            return redirect()->back()->with('error', 'Não foi possível associar permissões a esta função!...');
        }
    }
    //Adiciona novas funções na tabela
    public function addRoles(Request $request)
    {
       
        // $request = $request->get('role');

         Role::create($request->all());

        return redirect()->back()->with('success', 'Função Cadastrada com Sucesso!');
    }

    //Adiciona novas permissões
    public function addPermissions(Request $request)
    {
        // $request = $request->get('permission');

        // Permission::create($request);
Permission::create($request->all());

        return redirect()->back()->with('success', 'Permissões Cadastrada com Sucesso!');
    }

    //Editar alguma funções
    public function editRoles(Request $request, $id)
    {
        // $request = $request->get('role');

        $role = Role::findOrFail($id);

        $role->update($request->all());

        return redirect()->back()->with('success', 'Actualização efectuada com Sucesso!');
    }

    //Editar alguma permissões
    public function editPermissions(Request $request, $id)
    {
        // $request = $request->get('permission');

        $permission = Permission::find($id);

        $permission->update($request->all());

        return redirect()->back()->with('success', 'Actualização efectuada com Sucesso!');
    }

    //Deletar alguma funções
    public function deleteRoles($id)
    {
        //Fail, desfaz as alterações no banco de dados
        if ($role = Role::findOrFail($id)) {
            $role->delete();
            return redirect()->back()->with('success', 'Função eliminada com Sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não Foi Possível Eliminar');
        }
    }

    //Editar alguma permissões
    public function deletePermissions($id)
    {
        //Fail, desfaz as alterações no banco de dados
        if ($permission = Permission::findOrFail($id)) {
            $permission->delete();
            return redirect()->back()->with('success', 'Permissão eliminada com Sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não Foi Possível Eliminar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funcoes_Permissoes  $funcoes_Permissoes
     * @return \Illuminate\Http\Response
     */
    public function destroy( $funcoes_Permissoes)
    {
        //
    }
}
