<?php

namespace App\Http\Controllers;

use App\Models\ControlTarefa;
use App\Models\EstadosCivis;
use App\Models\EstadoTarefa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Responsavel;
use App\Models\Funcoes;
use App\Models\Projeto;
use App\Models\Genero;
use App\Models\Pessoa;
use App\Models\Tecnologia;
use App\Models\User;
use App\Models\TipoDocumentoIdentificacao;
use PDF;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Database\Schema\ForeignKeyDefinition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PessoaController extends Controller
{
    public function index()
    {
        try {
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();
            if ($responsavel_logado->funcao->id == 1 ||$responsavel_logado->funcao->id == 2) {
                $data['pessoas'] = Pessoa::with('projecto.estadoProjeto', 'funcao', 'genero', 'estadoCivil', 'user')->get();
            } else {
                return redirect()->back()->with('error', 'Nao foi possivel');
            }
            $data['funcao'] = Funcoes::all();
            $data['estadocivis'] = EstadosCivis::all();
            $data['generos'] = Genero::all();
            $data['tipo_documento_indentificacoes'] = TipoDocumentoIdentificacao::all();
            return Inertia::render('User/Responsavel', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errror', 'nao foi possivel');
        }
    }


    public function RemoveSpecialCharNumero($str)
    {
        $res = preg_replace('/[]\@\!\#\$\%\^\&\*\.\,\;\:\?\(\)\{\}\<\>\\\_\'\"\~\^\´\|\`\|\\=\+\/\[\-]+/', '', $str);
        $res = preg_replace("/[0-9]/", '', $res);
        $res = preg_replace('/\\s\\s+/', ' ', $res);
        return $res;
    }
    public function store(Request $request)
    {
        //dd(Hash::make("gp@2024"));
        DB::beginTransaction();

        try {
            $nome_responsavel = ucwords(mb_strtolower($this->RemoveSpecialCharNumero($request->get('nome_responsavel')), "utf-8"));
            $usernames = preg_split('/\s+/', mb_strtolower($nome_responsavel, "utf-8"), -1, PREG_SPLIT_NO_EMPTY);
            $nomecompleto = preg_split('/\s+/', ucfirst($nome_responsavel), -1, PREG_SPLIT_NO_EMPTY);
            $primeiroUltimoNome = head($nomecompleto) . '.' . last($nomecompleto);
            $username = head($usernames) . '.' . last($usernames);
            $numero = $request->get('numero_identificacao');



            $user = User::create([
                'name' => $request->get('nome_responsavel'),
                'email' => isset($request->email_responsavel) ? $request->email_responsavel : null,
                'telefone' => isset($request->telefone_responsavel) ? $request->telefone_responsavel : null,
                'password' => Hash::make($numero),
                'username' => $username,
            ]);
            if ($user) {


                if ($request->get('funcao_id') == 1) {
                    $user->assignRole('Administrador');
                } elseif ($request->get('funcao_id') == 2) {
                    $user->assignRole('Gestor de Projecto');
                } elseif ($request->get('funcao_id') == 3) {
                    $user->assignRole('Analista de Sistema');
                } 
            }
            Pessoa::create([
                'nome_responsavel' => head($nomecompleto),
                'sobre_nome_responsavel' => last($nomecompleto),
                'numero_identificacao' => isset($request->numero_identificacao) ? $request->numero_identificacao : null,
                'email_responsavel' => isset($request->email_responsavel) ? $request->email_responsavel : null,
                'telefone_responsavel' =>  $user->telefone,
                'user_id' => $user->id,
                'funcao_id' => $request->get('funcao_id'),
                'estado_civil_id' => $request->get('estado_civil_id'),
                'tipo_documento_identificacao_id' => isset($request->tipo_documento_identificacao_id) ? $request->tipo_documento_identificacao_id : null,
                'genero_id' => $request->get('genero_id'),
                'chefe_area' => $request->get('chefe_area'),
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Responsavel : ' . $nome_responsavel . ' Registrado com sucesso!');
        } catch (\Exception $e) {
            // Rollback and then redirect
            DB::rollback();
            //    dd($e->getMessage());

            return redirect()->back()->with('error', 'Não foi possível realizar a operação ');
        }
    
    }

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $responsavel = Pessoa::find($id);
        $user_id = $responsavel->user_id;
        $user = User::find($user_id);

        // dd($responsavel,$user);
        DB::beginTransaction();

        try {
            $nome_responsavel = ucwords(mb_strtolower($this->RemoveSpecialCharNumero($request->get('nome_responsavel')), "utf-8"));
            $usernames = preg_split('/\s+/', mb_strtolower($nome_responsavel, "utf-8"), -1, PREG_SPLIT_NO_EMPTY);
            $username = head($usernames) . '.' . last($usernames);
            $nomecompleto = preg_split('/\s+/', ucfirst($nome_responsavel), -1, PREG_SPLIT_NO_EMPTY);
            $numero = $request->get('numero_identificacao');

            $user->update([
                'name' => $nome_responsavel,
                'email' => isset($request->email_responsavel) ? $request->email_responsavel : null,
                'telefone' => isset($request->telefone_responsavel) ? $request->telefone_responsavel : '',
                'password' => Hash::make($numero),
                'username' => $username,
            ]);
            if ($user) {


                if ($request->get('funcao_id') == 1) {
                    $user->assignRole('Administrador');
                } elseif ($request->get('funcao_id') == 2) {
                    $user->assignRole('Gestor de Projecto');
                } elseif ($request->get('funcao_id') == 3) {
                    $user->assignRole('Analista de Sistema');
                }
            }

            $responsavel->update([
                'nome_responsavel' => head($nomecompleto),
                'sobre_nome_responsavel' => last($nomecompleto),
                'numero_identificacao' => isset($request->numero_identificacao) ? $request->numero_identificacao : '',
                'email_responsavel' => isset($request->email_responsavel) ? $request->email_responsavel : null,
                'telefone_responsavel' =>  $user->telefone,
                'user_id' => $user->id,
                'funcao_id' => $request->get('funcao_id'),
                'estado_civil_id' => $request->get('estado_civil_id'),
                'tipo_documento_identificacao_id' => isset($request->tipo_documento_identificacao_id) ? $request->tipo_documento_identificacao_id : null,
                'genero_id' => $request->get('genero_id'),
            ]);
        } catch (\Exception $e) {
            // Rollback and then redirect
            DB::rollback();
            // back to form with errors
            dd($e->getMessage());

            return redirect()->back()->with('error', 'Não foi possível realizar a operação ');
        }
        DB::commit();
        return redirect()->back()->with('success', 'Responsavel : ' . $nome_responsavel . ' Registrado com sucesso!');
    }

    public function ativar_chefe_area(Request $request)
    {
        $funcionario = Pessoa::where('id', $request->id)->first();
        $funcionario->update([
            'chefe_area' => 'SIM',
        ]);

        return redirect()->back()->with('success', 'Chefe de área activado com sucesso');
    }

    public function desativar_chefe_area(Request $request)
    {
        $funcionario = Pessoa::where('id', $request->id)->first();
        $funcionario->update([
            'chefe_area' => 'NAO',
        ]);
        return redirect()->back()->with('success', 'Chefe de área desactivado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */

    public function membros_equipa_pdf()
    {
        $pessoas = Pessoa::with('projecto.estadoProjeto', 'funcao', 'genero', 'estadoCivil', 'user')
            ->where('deleted_at', null)->get();
        //  dd($projeto);
        // $projeto = Projeto::with('responsavel')->get();
        $pdf = PDF::loadView('arquivo_pdf_responsaveis', [
            'arquivo_pdf_responsaveis' => $pessoas,
            'dataimpressao' => date('Y-m-d')
        ]);

    }
    public function destroy($id)
    {
        try {

            $responsavel = Pessoa::find($id);
            $responsavel->delete();
        } catch (\Throwable $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível eliminar o responsavel por que ele está relacionado uma ou mais tarefas');
            //throw $th;
        }


        //

    }
}
