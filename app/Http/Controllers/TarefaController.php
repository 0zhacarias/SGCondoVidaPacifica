<?php

namespace App\Http\Controllers;

use App\Mail\notificar_tarefa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\ControlTarefa;
use App\Models\EquipaProjecto;
use App\Models\EstadoTarefa;
use App\Models\PercentagemTarefa;
use App\Models\Funcoes;
use App\Models\Responsavel;
use App\Models\Notificacao;
use App\Models\NotificacaoMembro;
use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\TarefasHistoricos;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PDF;

use function PHPUnit\Framework\isNan;

class TarefaController extends Controller
{protected $modelUser;
    public function __construct()
    {
        $this->modelUser = new User;

    }

    public function index()

    {
        //Restrição para os usuarios logados
        try {
            // $p=PercentagemTarefa::with('updatedPercentagem')->get();
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->withCount('equipaProjecto')->first();
            $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();
            if ($responsavel_logado->funcao->id == 1 || $responsavel_logado->funcao->id == 2) {

                $data['tarefas'] = Tarefa::orderBy('created_at', 'desc')->get();
                $data['todastarefas'] = Tarefa::all();
                $data['projetos'] = Projeto::all();
                $data['responsaveis'] = Pessoa::with('funcao')->get();
            } else if ($responsavel_logado->chefe_area == "SIM" || $responsavel_logado->funcao->id == 3) {
                if ($responsavel_logado->equipa_projecto_count > 0) {
                    $equipa_projecto = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->get();
                    $data['projetos'] = Projeto::whereIn('id', $equipa_projecto)->get();
                    $data['tarefas'] = Tarefa::whereIn('projecto_id', $equipa_projecto)
               
                        ->orderBy('created_at', 'desc')->get();
                    $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();

                } else {
                    $data['projetos'] = [];
                    $data['tarefas'] = [];
                }
            } else {
                $data['tarefas'] = Tarefa::whereIn('created_by', $responsavel_logado)->orderBy('created_at', 'desc')->get();
                $id_responsaveis_projeto = Pessoa::where('user_id', auth()->user()->id)->select('id')->with('equipaProjecto')->get();
                $data['projetos'] = Projeto::where('responsavel_id', $responsavel_logado->id)->get();
                $proj = Projeto::where('responsavel_id', $responsavel_logado->id)->get();
                $equipa_projecto = EquipaProjecto::whereIn('projecto_id', $proj)->select('responsavel_id')->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();
                // $equipa_projecto = EquipaProjecto::whereIn('responsavel_id', $id_responsaveis_projeto)->select('projecto_id')->get();
                // dd( $data['projetos'] ,$id_responsaveis_projeto,$data['tarefas'] );

            }

            $data['estado_tarefas'] = EstadoTarefa::all();
            return Inertia::render('User/Tarefa', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    public function index_tarefa_responsavel()
    {
        $data['estado_tarefas'] = EstadoTarefa::all();
        $equipa_projecto = null;
        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();
        $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();

        $id_responsaveis_projeto = Pessoa::where('user_id', auth()->user()->id)->with('equipaProjecto')->withCount('equipaProjecto')->first();

        if ($responsavel_logado->funcao->id == 2 || $responsavel_logado->funcao->id == 1) {
            $data['projetotarefa'] = Projeto::where('responsavel_id', $responsavel_logado->id)->orderBy('created_at', 'desc')
                ->get();
            $data['tarefas'] = Tarefa::where('created_by', auth()->user()->id)
                ->orderBy('created_at', 'desc')->get();
        } else if ($responsavel_logado->chefe_area == "SIM" || $responsavel_logado->funcao->id == 3) {
            $data['tarefas'] = Tarefa::whereIn('id', $controltarefa)
               
                ->orderBy('created_at', 'desc')->get();
            if ($id_responsaveis_projeto->equipa_projecto_count > 0) {
                $equipa_projecto = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->get();
                $data['projetotarefa'] = Projeto::whereIn('id', $equipa_projecto)->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();
                // dd($data['projetotarefa']);
            } else {
                $data['projetotarefa'] = [];
            }
            return Inertia::render('User/TarefaResponsavel', $data);
            // return Inertia::render('User/Tarefa', $data);
        } else {
            $data['tarefas'] = Tarefa::whereIn('id', $controltarefa)->orderBy('created_at', 'desc')->get();
            if ($id_responsaveis_projeto->equipa_projecto_count > 0) {
                $equipa_projecto = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->where('deleted_at', null)->select('projecto_id')->get();
                $data['projetotarefa'] = Projeto::whereIn('id', $equipa_projecto)->get();
                $data['responsaveis'] = Pessoa::with('funcao')->whereIn('id', $controltarefa)->orderBy('created_at', 'desc')->get();
            } else {
                $data['projetotarefa'] = Projeto::where('id', $equipa_projecto)->get();
            }
        }
        $data['todastarefas'] = Tarefa::all();      
          return Inertia::render('User/TarefaResponsavel', $data);
    }

    public function  tarefas_projectos($id)
    {

        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();
        $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();
        $id = base64_decode(base64_decode(base64_decode($id)));
        $data['projecto_marcado'] = intval($id);
        $data['estado_tarefas'] = EstadoTarefa::all();
        $chefe[]=$responsavel_logado->id;
        if ($responsavel_logado->funcao->id == 1 || $responsavel_logado->funcao->id == 2) {
            $data['tarefas'] = Tarefa::orderBy('created_at', 'desc')->where('projecto_id', $id)->get();
            $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();
            $data['projetos'] = Projeto::all();


            $equipa_projecto = EquipaProjecto::
              //  whereNotIn('responsavel_id', $chefe)->
                where('projecto_id',$id)->where('deleted_at',null)->
                select('responsavel_id')->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();

           // $data['responsaveis'] = Pessoa::with('funcao')->get();
            return Inertia::render('User/Bloco', $data);
        } else if ($responsavel_logado->chefe_area == "SIM" || $responsavel_logado->funcao->id == 3) {
            $equipa_projecto = null;
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();

            $data['tarefas'] = Tarefa::
                where('projecto_id', $id)->orderBy('created_at', 'desc')->get();
            $id_responsaveis_projeto = Pessoa::where('user_id', auth()->user()->id)->with('equipaProjecto')->withCount('equipaProjecto')->first();
            $chefe[]=$responsavel_logado->id;

            if ($id_responsaveis_projeto->equipa_projecto_count > 0) {
                $equipa_projecto = EquipaProjecto::
               // whereNotIn('responsavel_id', $chefe)->
                where('projecto_id',$id)->where('deleted_at',null)->
                select('responsavel_id')->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();
            }
        //  dd( $equipa_projecto,$data['responsaveis'],$responsavel_logado->id,$id);
            return Inertia::render('User/Bloco', $data);
        } else {
            $equipa_projecto = null;
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id,)->first();
            $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();
            $data['tarefas'] = Tarefa::whereIn('id', $controltarefa)->where('projecto_id', $id)->orderBy('created_at', 'desc')->get();
            $id_responsaveis_projeto = Pessoa::where('user_id', auth()->user()->id)->with('equipaProjecto')->withCount('equipaProjecto')->first();
            if ($id_responsaveis_projeto->equipa_projecto_count > 0) {
                $equipa_projecto = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->get();
                $data['projetotarefa'] = Projeto::whereIn('id', $equipa_projecto)->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $equipa_projecto)->with('funcao')->get();
            }
            return Inertia::render('User/TarefaResponsavel', $data);
        }



        /* dd($data); */

        // return Inertia::render('User/Tarefa_teste', $data);

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
    public function store(Request $request)
    {
        // DB::beginTransaction();
        //dd($request);
        try {

            $data_aux = strtotime($request->get('data_inicio_real') . "+" . $request->get('tempo_execucao') . "days");
            $data_termino = date("Y-m-d H:i:s", $data_aux);

            $tarefa = Tarefa::create([
                'nome_tarefa' => $request->get('nome_tarefa'),
                'descricao' => $request->get('descricao'),
                'data_inicio_real' => $request->get('data_inicio_real'),
                'tempo_execucao' => $request->get('tempo_execucao'),
                'data_fim_real' => $data_termino,
                'percentagem' => 0,
                'projecto_id' => $request->get('projecto_id'),
                'estado_tarefa_id' => 1,
                'created_by' => auth()->user()->id,
            ]);
            // armazena toda ocorrencia da tarefa para a variavel$vtarefa

            foreach ($request->get('responsavel_id') as $vresponsavel) {/* Está linha serve para percorrer um objeto de responsaveis* */
                ControlTarefa::create([
                    'tarefa_id' => $tarefa->id,
                    'responsavel_id' => $vresponsavel
                ]);
            }


            // Alterar o valor do campo da tabela estadoProjeo da tabela Projeto
            $projecto = Projeto::find($request->get('projecto_id'));
            if ($projecto->estadoProjeto->id != 2) {
                $projecto->update(['estado_projeto_id' => 2]);
            }
            // notificação

            $projecto = Projeto::where('id', $tarefa->projecto_id)->first();
            $nomeProjecto = $projecto->nome_proj;
            $estado = EstadoTarefa::where('id', $tarefa->estado_tarefa_id)->first();
            $nomeEstado = $estado->designacao;
            $url = action([TarefaController::class, 'index']);
            $criadoPor = User::where('id', $tarefa->created_by)->first();
            $nomecriadoPor = $criadoPor->name;

            /* dd($vtarefa); */

            // Mail::to('flaviodecarvalho6@gmail.com')->send(new notificar_tarefa($vtarefa->nome_tarefa, $vtarefa->descricao, $vtarefa->data_inicio_real, $vtarefa->tempo_execucao, $vtarefa->data_fim_real, $vtarefa->percentagem, $nomecriadoPor, $nomeProjecto, $nomeEstado, $url));
            return redirect()->back()->with('success', 'Foi cadastra com sucesso a tarefa');
        } catch (\Exception $e) {
            // DB::rollback();
            // dd($e->getMessage());
            // DB::commit();
            return redirect()->back()->with('error', 'Não foi possivel cadastrar essa tarefa');
            //    return redirect('/tarefas/tarefa');
            //throw $th;
        }

        //return Redirect('/tarefas/tarefa');
    }
    //Inserindo multipos elemento numa rela;\ao muito para muitos
    public function adicionar_responsavel_tarefa(Request $request)
    {
        try {

            foreach ($request->get('responsavel_id') as $responsavel) {/* Está linha serve para percorrer um objeto de responsaveis* */
                ControlTarefa::create([
                    'tarefa_id' => $request->get('tarefa_id'),
                    'responsavel_id' => $responsavel
                ]);
            }
        } catch (\Exception $e) {
            // DB::rollback();
            // dd($e->getMessage());
            // DB::commit();
            return redirect()->back()->with('error', 'Não foi possivel cadastrar essa tarefa');
            //    return redirect('/tarefas/tarefa');
            //throw $th;
        }

        $vtarefa = Tarefa::where('id', $request->tarefa_id)->first();
        $projecto = Projeto::where('id', $vtarefa->projecto_id)->first();
        $nomeProjecto = $projecto->nome_proj;
        $estado = EstadoTarefa::where('id', $vtarefa->estado_tarefa_id)->first();
        $nomeEstado = $estado->designacao;
        $url = action([TarefaController::class, 'index']);
        $criadoPor = User::where('id', $vtarefa->created_by)->first();
        $nomecriadoPor = $criadoPor->name;

        /* dd($vtarefa); */

        //Mail::to('flaviodecarvalho6@gmail.com')->send(new notificar_tarefa($vtarefa->nome_tarefa, $vtarefa->descricao, $vtarefa->data_inicio_real, $vtarefa->tempo_execucao, $vtarefa->data_fim_real, $vtarefa->percentagem, $nomecriadoPor, $nomeProjecto, $nomeEstado, $url));

        return redirect()->back()->with('success', 'Foi cadastra com sucesso a tarefa');
    }

    public function  aceitar_tarefa(Request $request, $id)
    {
        try {
            $tarefa = Tarefa::find($id);

            $data['estado_tarefa_id'] = 2;
            $data['aceitado_by'] = auth()->user()->id;
            $data['data_aceitacao'] = date("Y-m-d");
            $tarefa->update($data);

            $notificacao = Notificacao::create([
                'designacao' => auth()->user()->name . "\n Aceitou a tarefa: " . $tarefa->nome_tarefa,
                'created_by' => auth()->user()->id,
                'tipo_notificacao' => 1,
                'estado_notificacao_id' => 1,
                'tarefa_id' => $tarefa->id,
            ]);
            //  dd($tarefa->created_by );
            // dd($tarefa, $id_responsavel_projeto,$percentagem,$notificacao );
            NotificacaoMembro::create(
                [
                    'notificacao_id' => $notificacao->id,
                    'user_id' => $tarefa->created_by,
                ]
            );

            return redirect()->back()->with('success', 'A tarefa foi aceita');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possivel atualizar está tarefa ');
        }

        return redirect()->back()->with('success', 'A tarefa foi aceita');
    }


    public function rejeitar_tarefa(Request $request, $id)

    {

        try {

            $id_responsavel = Pessoa::where('user_id', $id)->select('id')->first();
            $tarefa = Tarefa::find($id);
            $tarefa->update([
                'estado_tarefa_id' => 6,
                'motivo_rejeicao' => $request->motivo_rejeicao,
                'rejeitado_by' => auth()->user()->id,
                'data_rejeicao' => date("Y-m-d"),

            ]);

            $notificacao = Notificacao::create([
                'designacao' => $request->motivo_rejeicao,
                'created_by' => auth()->user()->id,
                'tipo_notificacao' => 2,
                'estado_notificacao_id' => 1,
                'responsavel_id' => $id_responsavel->id,
                'tarefa_id' => $tarefa->id,
            ]);
            //  dd($tarefa->created_by );
            // dd($tarefa, $id_responsavel_projeto,$percentagem,$notificacao );
            NotificacaoMembro::create(
                [
                    'notificacao_id' => $notificacao->id,
                    'user_id' => $tarefa->created_by,
                ]
            );

            // dd($tarefa, $id_responsavel_projeto,$percentagem);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possível rejeitar essa tarefa', $th->getMessage());
        }

        return redirect()->back()->with('success', 'Essa tarefa foi rejeitada!');
    }

    public function  cancelamento_tarefa(Request $request, $id)
    {
        // Sair da tabela Projeto  para a tabela tarefa para comparar a chave estrangeira com o dado vindo do front.

        try {
            // auth()->user()->name
            $tarefa = Tarefa::find($id);
            $motivo =  "Aviso! " . $tarefa->nome_tarefa . " Foi cancelada ";
            // $motivo =  "Aviso !".$tarefa->nome_tarefa." Foi cancelada ".$request->motivo_cancelamento. " Enviado pelo Gestor de Projecto : ";

            $tarefa->update([
                'estado_tarefa_id' => 7,
                'motivo_cancelamento' => $motivo,
                'data_cancelamento' => date('Y-m-d'),
                'cancelado_by' => auth()->user()->id
            ],);
            // $percentagem = $request->get('percentagem');
            //    if ($percentagem == 0) {
            $responsaveis_tarefa = ControlTarefa::where('tarefa_id', $tarefa->id)->select('responsavel_id')->get();
            //dd($responsaveis_tarefa);
            $responsaveis = Pessoa::whereIn('id', $responsaveis_tarefa)
                ->select('user_id')->get();

          
            //   }

            // dd($responsaveis,$notificacao );
            // dd($tarefa, $id_responsavel_projeto,$percentagem,$notificacao );

            foreach ($responsaveis as $responsavel) {
                NotificacaoMembro::create(
                    [
                        'notificacao_id' => $notificacao->id,
                        'user_id' => $responsavel->user_id,
                    ]
                );
            }
            // dd($tarefa, $id_responsavel_projeto,$percentagem);

        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->back()->with('error', 'Não é possivel cancelar está tarefa');
        }

        return redirect()->back()->with('success', 'A tarefa foi Cancelada!');
    }


    public function  tarefa_concluido(Request $request, $id)
    {
        try {
            $tarefa = Tarefa::find($id);
            $percentagem = $request->get('percentagem');

            //dd($tarefa->estado_tarefa_id);
            if ($percentagem == 100 || $tarefa->estado_tarefa_id == 5) {
                $data['estado_tarefa_id'] = 4;
                $data['percentagem'] = 100;
            }


            $tarefa->update($data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'A percentagem não está ' . $percentagem . '% e deve estar à 100% concluida');
        }

        return redirect()->back()->with('success', 'Atualização da percentagem com sucesso');
    }

    public function  atualizar_percentagem_tarefa(Request $request, $id)
    {

        try {
            $tarefa = Tarefa::find($id);
            // $data['estado_tarefa']=$request->get();
            $data['percentagem'] = $request->get('percentagem');
            $data['percentagem_updated_by'] = auth()->user()->id;
            $data['data_updated_percentagem'] = date('Y-m-d H:i:s');

            if ($request->get('percentagem') == 100) {
                if (is_null($tarefa->created_by) && isset($tarefa->tickets_id)) {
                    //dd($request->get('percentagem'));
                    $codigo_responsavel_id=Pessoa::where('user_id',auth()->user()->id)->pluck('id')->first();
                    $data['estado_tarefa_id'] = 4;
                    //Contrução da API para criar dar como um ticket concluir pelo suporte
                   if($this->modelUser->getCanPermission('Criar ticket no HelpDesk')){


                    try {
                        $response = Http::post(
                            env('URL_HELPDESK') . '/api/concluir-tarefa-criar-ticket?tickek_id='
                                . $tarefa->tickets_id . '&codigo_responsavel_id=' .  $codigo_responsavel_id

                        );
                    } catch (\Exception $e) {

                        return response()->json(['error' => 'Não foi possível criar uma tarefa']);
                    }
                }
                } else {
                    $data['estado_tarefa_id'] = 3;
                }
            } else {
                $data['estado_tarefa_id'] = 2;
            }
            $tarefa->update($data);

            $percentagemtarefas = PercentagemTarefa::where('tarefa_id', $id)->get();
            foreach ($percentagemtarefas as $percentagemtarefa) {
                if ($percentagemtarefa->estado_percentagem_tarefas_id == 1) {

                    $percentagemtarefa->update(['estado_percentagem_tarefas_id' => 2]);
                }
            }
            PercentagemTarefa::create([
                'tarefa_id' => $id,
                'created_by' => date('Y-m-d H:i:s'),
                'designacao' => $request->get('designacao'),
                'percentagem' => $request->get('percentagem'),
                'estado_percentagem_tarefas_id' => 1,
                'update_by' => auth()->user()->id,

            ]);
            return redirect()->back()->with('success', 'Atualização da percentagem com sucesso');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possivel é ditar a percentagem de vido a alguma informação incorreta');
            //    return redirect('/tarefas/tarefa');
            //throw $th;
        }
    }


    public function add_tarefa_responsavel(Request $request)
    {

        try {
            $idprojeto = $request->projecto_id;
            // $responsavelProjecto = Projeto::where('id', $idprojeto)->select('responsavel_id')->first();

            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->select('id')->first();
            $data_aux = strtotime($request->get('data_inicio_real') . "+" . $request->get('tempo_execucao') . "days");
            $data_termino = date('Y-m-d', $data_aux);
            $data = $request->all();
            $data['estado_tarefa_id'] = 2;
            $data['percentagem'] = 0;
            $data['data_fim_real'] = $data_termino;
            $data['created_by'] = auth()->user()->id;
            $vtarefa = Tarefa::create($data);
            // $idtarefa = auth()->user()->id;;
            // $id = [$responsavel_logado->id, $responsavelProjecto->responsavel_id];
            $id = [$responsavel_logado->id];

            foreach ($id as $responsavel) {
                ControlTarefa::create([
                    'tarefa_id' => $vtarefa->id,
                    'responsavel_id' => $responsavel,
                ]);
            }
            return redirect()->back()->with('sucess', 'Foi cadastra com sucesso a tarefa');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possivel');
        }
    }
    public function listar_tarefa($id)
    {
        $id = base64_decode(base64_decode(base64_decode($id)));

        try {
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();
            $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)
                ->select('tarefa_id')
                ->get();
            $id_tarefa = Tarefa::whereIn('id', $controltarefa)->select('id')->get();
            $control_responsavel = ControlTarefa::whereIn('tarefa_id', $id_tarefa)->select('responsavel_id')->get();

            if ($responsavel_logado->funcao->id == 1) {
                $data['tarefas'] = Tarefa::
                where('estado_tarefa_id', $id)
                    ->orderBy('created_at', 'desc')->get();
                $data['todastarefas'] = Tarefa::get();
                $data['projetos'] = Projeto::get();
                $data['responsaveis'] = Pessoa::get();
                $data['estado_tarefas'] = EstadoTarefa::get();

                return Inertia::render('User/Tarefa', $data);
            } else if ($responsavel_logado->funcao->id == 2) {
                $data['tarefas'] = Tarefa::whereIn('id', $controltarefa)
                    ->where('estado_tarefa_id', $id)->orderBy('created_at', 'desc')->get();

                // }
                $data['todastarefas'] = Tarefa::whereIn('id', $controltarefa)->get();
                $data['projetos'] = Projeto::whereIn('id', $controltarefa)->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $control_responsavel)->get();
                $data['estado_tarefas'] = EstadoTarefa::get();
                // dd($id_tarefa, $control_responsavel, $data['responsaveis']);

                return Inertia::render('User/Tarefa', $data);
            } else {
       
                $data['tarefas'] = Tarefa::whereIn('id', $controltarefa)
                    ->where('estado_tarefa_id', $id)->orderBy('created_at', 'desc')->get();
                // }
                $data['todastarefas'] = Tarefa::whereIn('id', $controltarefa)->get();
                $data['projetos'] = Projeto::whereIn('id', $controltarefa)->get();
                $data['responsaveis'] = Pessoa::whereIn('id', $controltarefa)->get();
                $data['estado_tarefas'] = EstadoTarefa::get();


                return Inertia::render('User/TarefaResponsavel', $data);
            }
        } catch (\Exception $e) {
        }
    }
    public function ver_agora(Request $request)
    {
        $i = $request;


        $tarefa = Tarefa::whereIn('id', $i)->get();
        // dd($tarefa);
        return response()->json($tarefa);
    }

    public function filtrar_estado(Request $request)
    {
//dd($request);
if($request->get('projecto_id')){
    $projecto[]=$request->get('projecto_id');
}else{
    $projecto=Tarefa::select('projecto_id')->get();
}
        $data_inicial = $request->get('data_inicial');
        $data_final = $request->get('data_final');
        $tarefa_id = [];
        if ($request->get('responsavel_id')) {

            $tarefa_id = ControlTarefa::where('responsavel_id', $request->get('responsavel_id'))->select('tarefa_id')->get();
            // $responsavel=Pessoa::whereIn('id', $id_responsavel)->select('id')->get();

        } else {
            $tarefa_id = Tarefa::select('id')->get();
        }

        $estado_tarefa = [];
        if ($request->get('estado_tarefa_id')) {
            $estado_tarefa[] = $request->get('estado_tarefa_id');
        } else {
            $estado_tarefa = EstadoTarefa::select('id')->get();
        }
        if ($request->get('data_final')) {


            $tarefas = Tarefa::whereIn('id',  $tarefa_id)
                ->whereIn('projecto_id',  $projecto)
                ->whereIn('estado_tarefa_id', $estado_tarefa)
                ->where('data_inicio_real', '>=', $data_inicial)
                ->where('data_inicio_real', '<=', $data_final)
                ->orderBy('created_at', 'asc')->get();
        } 

        // verificar se é um administrador ou uma outra funçã no sistema

        return response()->json($tarefas);
    }
    public function filtrar_tarefa_responsavel(Request $request)
    {
        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)
            
            ->first();
        $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)
            ->select('tarefa_id')
            ->get();

        $data_inicial = $request->get('data_inicial');
        $data_final = $request->get('data_final');

        $estado_tarefa = [];
        if ($request->get('estado_tarefa_id')) {
            $estado_tarefa[] = $request->get('estado_tarefa_id');
        } else {
            $estado_tarefa = EstadoTarefa::select('id')->get();
        }

        if ($request->get('data_final')) {
            $tarefas = Tarefa::whereIn('id', $controltarefa)
                ->whereIn('estado_tarefa_id', $estado_tarefa)
                ->where('data_inicio_real', '>=', $data_inicial)
                ->where('data_inicio_real', '<=', $data_final)

                ->orderBy('created_at', 'asc')->get();
        } else {

            $tarefas = Tarefa::whereIn('id', $controltarefa)
                ->whereIn('estado_tarefa_id', $estado_tarefa)
                ->orderBy('data_inicio_real', 'asc')
                ->get();
        }
        return response()->json($tarefas);
    }



    public function export_tarefa_pdf($id_tarefa)
    {
        $tarefa = Tarefa::where('id', $id_tarefa)->get();
        $pdf = PDF::loadView('arquivo_pdf_tarefa', [
            'arquivo_pdf_tarefa' => $tarefa
        ]);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    public function listar_pdf_tarefas($id)
    {
        set_time_limit(1024);

        if (is_numeric($id)) {
            $tarefa = Tarefa::where('projecto_id', $id)->get();
        } else {
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->withCount('equipaProjecto')->first();
            $equipa_projecto = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->get();
            if ($responsavel_logado->chefe_area == "SIM" || $responsavel_logado->funcao->id == 3) {
                $tarefa = Tarefa::
                    whereIn('projecto_id', $equipa_projecto)->get();
            } else {
                $tarefa = Tarefa::get();
            }
        }
        $pdf = PDF::loadView('pdf_listar_tarefa', [
            'pdf_listar_tarefa' => $tarefa,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    public function listar_meus_pdf_tarefas($id)
    {
        set_time_limit(0);

        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();
        $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)->select('tarefa_id')->get();
        if (is_numeric($id)) {
            $tarefa = Tarefa::whereIn('id', $controltarefa)->where('projecto_id', $id)
                ->get();
        } else {
            $tarefa = Tarefa::whereIn('id', $controltarefa)
                ->get();
        }
        $pdf = PDF::loadView('pdf_listar_tarefa', [
            'pdf_listar_tarefa' => $tarefa,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }

    public function fitrar_projeto_responsavel(Request $request)
    {

        // $responsaveis= Pessoa::whereNotIn('id',$controltarefa)->get();

    }

    /**
     * Display the specified resource.
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
        try {
            $tarefa = Tarefa::find($id);
            $estado_tarefa_id =$tarefa->estado_tarefa_id;
            $data_aux = strtotime($request->get('data_inicio_real') . "+" . $request->get('tempo_execucao') . "days");
            $data_termino = date("Y-m-d H:i:s", $data_aux);
            $data = $request->all();
            if($data_termino > $tarefa->data_fim_real && ($tarefa->estado_tarefa_id==5 ||$tarefa->estado_tarefa_id==6) ){
                $estado_tarefa_id = 2;
            }
            $tarefa->update([
                 'nome_tarefa' => $request['nome_tarefa'],
                 'descricao' => $request['descricao'],
                 'data_inicio_real' => $request['data_inicio_real'],
                 'estado_tarefa_id' => $estado_tarefa_id,
                 'data_fim_real' => $data_termino,
                 'tempo_execucao' =>$request->get('tempo_execucao'),
                 'projecto_id' => $request->get('projecto_id'),
                 'updated_by' =>  auth()->user()->id,
             ]);
             $responsaveis=$request->get('responsavel_id');
             if(isset($responsaveis)){
                foreach ($responsaveis as $responsavel) {/* Está linha serve para percorrer um objeto de responsaveis* */
                ControlTarefa::create([
                    'tarefa_id' => $tarefa->id,
                    'responsavel_id' => $responsavel
                ]);
             }

            }
           

            return redirect()->back()->with('success', 'Atualização feita com sucesso');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', 'Não foi possivel actualizar os dados', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $tarefa = Tarefa::find($id);
        $tarefa->controlTarefas()->delete();
        $tarefa->delete();
        return redirect()->back()->with('success', 'Dados eliminado com sucesso');
    }
}
