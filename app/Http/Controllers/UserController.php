<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Responsavel;
use App\Models\Funcoes;
use Illuminate\Support\Facades\Hash;
use App\Models\Genero;
use App\Models\Pessoa;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use PDF;

class UserController extends Controller
{

    public function index()
    {
        $user=new FinancaController();

        $data['usuarios'] = User::with('responsavel.apartamento.bloco.sindico')->get();
        $data['apartamentos'] = Apartamento::select('id','designacao')->whereNull('condomino_id')->get();
        if ($user->pessoa()['funcao_id']==1) {
            $data['roles'] = Role::get();
        } else {
            $data['roles'] = Role::where('id',3)->get();
        }
        $data['permissions'] = Permission::all();
        //dd($data);
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
        //dd($request);
        DB::beginTransaction();

        try {
            $usernames = preg_split('/\s+/', mb_strtolower($request->name, "utf-8"), -1, PREG_SPLIT_NO_EMPTY);
            $username = head($usernames) . '.' . last($usernames);
            
            $nomecompleto = preg_split('/\s+/', ucfirst($request->name), -1, PREG_SPLIT_NO_EMPTY);
         //   dd(head($nomecompleto));
            $user = User::create([
                'name' => isset($request->name) ? $request->name : '',

                'email' => isset($request->email) ? $request->email : null,
                'telefone' => isset($request->telefone) ? $request->telefone : '',
                'password' => Hash::make("sigcond"),
                'username' => $username,
            ]);
             /* if ($user) {


                if ($request->get('funcao_id') == 1) {
                    $user->assignRole('Administrador');
                } elseif ($request->get('funcao_id') == 2) {
                    $user->assignRole('Sindico');
                } elseif ($request->get('funcao_id') == 3) {
                    $user->assignRole('Condomino');
                } 
            }  */
            $user->assignRole($request->roles);
          $pessoa=Pessoa::create([
                'nome_pessoa' => head($nomecompleto),
                'sobre_nome_pessoa' => last($nomecompleto),
                'numero_identificacao' => isset($request->numero_identificacao) ? $request->numero_identificacao : $user->telefone,
                'email_pessoa' => isset($request->email) ? $request->email : null,
                'telefone_pessoa' =>  $user->telefone,
                'user_id' => $user->id,
                'funcao_id' => Funcoes::where('designacao',$request->roles)->pluck('id')->first(),
                'estado_civil_id' => null,
                'tipo_documento_identificacao_id' => isset($request->tipo_documento_identificacao_id) ? $request->tipo_documento_identificacao_id : null,
                'genero_id' => null,
                'created_by' => auth()->id(),
            ]);
            $apartamento=Apartamento::find(request()->apartamento_id)->update(['condomino_id'=>$pessoa->id]);
            // RemoveRole();



            // return redirect('users/user');
            DB::commit();
            return redirect()->back()->with('success ', 'Foi cadastrado com sucesso o utilizador');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Não foi possível realizar está operação.'.$e->getMessage());
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
