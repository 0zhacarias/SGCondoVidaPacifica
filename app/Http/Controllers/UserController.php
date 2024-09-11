<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Responsavel;
use App\Models\Funcoes;
use Illuminate\Support\Facades\Hash;
use App\Models\Genero;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use PDF;

class UserController extends Controller
{

    public function index()
    {
        $data['usuarios'] = User::all();
        $data['funcoes'] = Funcoes::all();
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        return Inertia::render('User/User1', $data);
    }

    public function perfil()
    {
            $data['generos'] = Genero::all();
            //
            $data['usuario'] = User::where('id', auth()->user()->id)->with('responsavel.funcao', 'responsavel.genero', 'responsavel.estadoCivil')->first();
            //    dd($data['usuario']);
            return Inertia::render('User/Perfil', $data);
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
         dd($request);
        DB::beginTransaction();

        try {
            $usernames = preg_split('/\s+/', mb_strtolower($request->name, "utf-8"), -1, PREG_SPLIT_NO_EMPTY);
            $username = head($usernames) . '.' . last($usernames);

            $user = User::create([
                'name' => isset($request->name) ? $request->name : '',

                'email' => isset($request->email) ? $request->email : null,
                'telefone' => isset($request->telefone) ? $request->telefone : '',
                'password' => Hash::make("sigcond"),
                'username' => $username,
            ]);
            $user->assignRole($request->roles);
            // RemoveRole();



            // return redirect('users/user');
            DB::commit();
            return redirect()->back()->with('success ', 'Foi cadastrado com sucesso');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Não foi possível realizar está operação.');
        }

   
    }
    public function edit(Request  $request)
    {
        // Atualizacao
    }
    public function update(Request  $request, $id)
    {
        try {
            $user = User::find($id);

            $user->update(
                [
                    'name' => isset($request->name) ? $request->name : '',
                    'email' => isset($request->email) ? $request->email : null,
                    'telefone' => isset($request->telefone) ? $request->telefone : '',
                    'username' => isset($request->username) ? $request->username : '',
                    // 'roles_id'=>$request->get('roles_id')

                ]

            );
            if ($user) {

                if ($request->get('funcao_id') == 1) {
                    $user->assignRole('Administrador');
                } elseif ($request->get('funcao_id') == 2) {
                    $user->assignRole('Gestor de Projecto');
                } elseif ($request->get('funcao_id') == 3) {
                    $user->assignRole('Analista de Sistema');
                } elseif ($request->get('funcao_id') == 4) {
                    $user->assignRole('Programador');
                } elseif ($request->get('funcao_id') == 5) {
                    $user->assignRole('DB');
                } elseif ($request->get('funcao_id') == 6) {
                    $user->assignRole('Director Tecnico');
                }
            }

            return redirect()->back()->with('success', 'Atualização dos dados do ' . $request->name . ' foi realizado com sucesso');

            // dd($user);
            // $user->assignRole($request->roles);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Atualização dos dados do ' . $request->name . ' sem sucesso');
        }



        // $user=User::find($id);
        // $user->update($request->all());
        // return redirect('users/user');
        return redirect()->back()->with('success', 'Atualização dos dados do ' . $request->name . ' foi realizado com sucesso');
    }
    public function atualizar_senha(Request $request, $id)
    {
        // dd($request);
        $idusuario = User::find($id);
        $password_antiga = $request->get('password_antiga');
        if (Hash::check($password_antiga, $idusuario->password)) {
            $data['password'] = Hash::make($request->password);
            $idusuario->update($data);
            return redirect()->back()->with('success', 'A sua senha foi atualizado com sucesso!');
        } else
            return redirect()->back()->with('error', 'Não foi possivel atualizar a sua senha');
        // return redirect()->back()->with('success', 'A sua senha foi atualizado com sucesso!');
        // return redirect('users/perfil');


    }

    public function RemoveSpecialCharNumero($str)
    {
        $res = preg_replace('/[]\@\!\#\$\%\^\&\*\.\,\;\:\?\(\)\{\}\<\>\\\_\'\"\~\^\´\|\`\|\\=\+\/\[\-]+/', '', $str);
        $res = preg_replace("/[0-9]/", '', $res);
        $res = preg_replace('/\\s\\s+/', ' ', $res);
        return $res;
    }
    public function  atualizar_perfil(Request $request, $id)
    {
        $nome_responsavel = ucwords($request->get('name'), "utf-8");
        $usernames = preg_split('/\s+/', $nome_responsavel,  -1, PREG_SPLIT_NO_EMPTY);
        $user = User::find($id);
        $user->update([
            'name' => isset($request->name) ? $request->name : '',
            'email' => isset($request->email) ? $request->email : null,
            'telefone' => isset($request->telefone) ? $request->telefone : '',
            'username' => isset($request->username) ? $request->username : ''
        ]);
        $responsavel = Responsavel::where('user_id', $user->id)->first();
        if ($responsavel != null) {
            $responsavel->update([
                'nome_responsavel' => head($usernames),
                'sobre_nome_responsavel' => last($usernames),
                'email_responsavel' => $user->email,
                'telefone_responsavel' => $user->telefone
            ]);
        }

        return redirect()->back()->with('success', 'A sua senha foi atualizado com sucesso!');

        // return redirect('users/perfil');
    }
    public function export_perfil_pdf($id)
    {
        set_time_limit(0);
        $perfil = User::with('responsavel.genero', 'responsavel.estadoCivil', 'responsavel.funcao')->where('id', $id)->get();
        $pdf = PDF::loadView('arquivo_pdf_perfil', [
            'arquivo_pdf_perfil' => $perfil
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    // public function arquivo_pdf_projecto(){
    //     $products = Projeto::all();

    // return \PDF::loadView('arquivo_pdf_projecto', compact('producto'))->setPaper('a4', 'landscape')
    //             // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
    //             ->download('nome-arquivo-pdf-gerado.pdf');
    // }


    public function show(Request  $request)
    {
        //
    }
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
        } catch (\Throwable $e) {
            redirect()->back()->with('error', 'Não foi possível eliminar o responsavel.');
        }
        redirect()->back()->with('success', 'Operação realizada com sucesso!.');
    }
}
