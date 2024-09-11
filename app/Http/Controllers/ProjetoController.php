<?php

namespace App\Http\Controllers;

use App\Models\AreaEmpresa;
use App\Models\ControlProjectoAreaTipoProjectos;
use App\Models\ControlProjectoTecnologia;
use App\Models\ControlProjectoTecnologiaTipoProjectos;
use App\Models\EstadoProjeto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\Pessoa;
use App\Models\ControlTarefa;
use App\Models\ControlTecnologiaTipoProjecto;
use App\Models\EquipaProjecto;
use App\Models\Funcoes;
use App\Models\Notificacao;
use App\Models\NotificacaoMembro;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectosArquivosUpload;
use App\Models\Tecnologia;
use App\Models\TipoProjecto;
use Illuminate\Support\Facades\DB;
use PDF;

class ProjetoController extends Controller
{
    public function index()
    {

        //Atualizar o projecto
        $projetos = Projeto::orderBy('created_at', 'asc')->where('deleted_at', null)->withCount('tarefas')->get();
        $c = 0;
        foreach ($projetos as $projeto) {

            $tarefa = Tarefa::where('projecto_id', $projeto->id)->where('deleted_at', null)->whereIn('estado_tarefa_id', [1, 2, 3, 5])->get();

            if (count($tarefa) == 0) {

                Projeto::where('id', $projeto->id)->where('deleted_at', null)->where('estado_projeto_id', '!=', 1)->update([
                    'estado_projeto_id' => 4
                ]);
                //alterado em 16.03 se o projecto estiver concluido e depois é adicionado mais uma tarefa.
            } else {
                Projeto::where('id', $projeto->id)->where('deleted_at', null)->where('estado_projeto_id', 4)->update([
                    'estado_projeto_id' => 2
                ]);
            }
        }
        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();

        if ($responsavel_logado->funcao->id == 2) {
            $data['projetos'] = Projeto::where('responsavel_id', $responsavel_logado->id)
                ->where('deleted_at', null)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        $data['projetos'] = Projeto::orderBy('created_at', 'desc')->get();
        $data['contar'] = Projeto::count();

        // $gestores=Projeto::select('pessoas_id')->get();
        $id = [2];
        $data['gestores'] = Pessoa::whereIn('funcao_id', $id)->get();
        // $data['filtroGestores'] = Pessoa::whereIn('id', $gestores)->get();
        $data['filtroProjectos'] = Projeto::get();
        $data['estadoProjetos'] = EstadoProjeto::all();
        $membros_id = [3, 4, 5];
        $data['funcaoEquipe'] = Funcoes::whereIn('id', $membros_id)->get();
        return Inertia::render('User/Projeto', $data);
    }

    public function index_projectos()
    {
        $projetos = Projeto::with('tarefas', 'pessoas', 'equipaProjecto')->orderBy('created_at', 'asc')->withCount('tarefas')->get();
        $c = 0;
        $colecaotarefa = [];

        foreach ($projetos as $projeto) {
            $c++;
            $percentagem = 0;

            $quant_tarefa = $projeto->tarefas_count;
            foreach ($projeto->tarefas as $tarefa) {
                $percentagem = $percentagem + $tarefa->percentagem;
                $colecaotarefa[] = $percentagem + $tarefa->percentagem;
            }
            $data['total'][] = $quant_tarefa == 0 ? 0 : $percentagem / $quant_tarefa;
        }

        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();
        if ($responsavel_logado->funcao->id == 1) {
            $data['projetos'] = Projeto::orderBy('created_at', 'desc')
                ->where('deleted_at', null)
                ->get();
            $data['contar'] = Projeto::count();
            $data['filtroResponsaveis'] = Pessoa::with('equipaProjecto')
                ->whereNotIn('funcao_id', [1])
                ->get();
        } else if ($responsavel_logado->funcao->id == 2) {
            $data['projetos'] = Projeto::where('responsavel_id', $responsavel_logado->id)->orderBy('created_at', 'desc')
                ->get();
            $proj_id = Projeto::where('responsavel_id', $responsavel_logado->id)->select('id')->get();

            $data['filtroResponsaveis'] = Pessoa::with('equipaProjecto')
                ->whereHas('equipaProjecto', function ($query) use ($proj_id) {
                    $query->whereIn('projecto_id', $proj_id)->select('responsavel_id');
                })->get();
        } else {

            $equipa = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->where('deleted_at', null)->get();
            $data['projetos'] = Projeto::whereIn('id', $equipa)->where('deleted_at', null)->get();
        }
        $id = [2];
        $data['gestores'] = Pessoa::whereIn('funcao_id', $id)->get();
        $data['estadoProjetos'] = EstadoProjeto::all();
        $membros_id = [3, 4, 5];
        $data['funcaoEquipe'] = Funcoes::whereIn('id', $membros_id)->get();
        return Inertia::render('User/MeuProjeto', $data);
    }



    public function projectos_concluido()
    {
        // $controltarefas=ControlTarefa::where('tarefa_id',2);

        $projetos = Projeto::orderBy('created_at', 'asc')->withCount('tarefas')->get();
        $c = 0;

        //$quant_tarefa=

        foreach ($projetos as $projeto) {
            $c++;
            $percentagem = 0;
            $quant_tarefa = $projeto->tarefas_count;
            foreach ($projeto->tarefas as $tarefa) {

                $percentagem = $percentagem + $tarefa->percentagem;
            }

            $data['total'] = $quant_tarefa == 0 ? 0 : $percentagem / $quant_tarefa;
            // if($c==2){
            //     break;
            //  }
        }

        return response()->json($data);
    }

    public function estatistica_projetos()
    {
        $data['estadoProjetos'] = EstadoProjeto::all();
        $data['projetos'] = Projeto::get();
        //    dd( $data['projetos']);

        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();
        if ($responsavel_logado->funcao->id == 1) {
            $data['projetos'] = Projeto::orderBy('created_at', 'desc')
                ->get();

            $data['ProjetosPendente'] = Projeto::where('estado_projeto_id', '1')->count();
            $data['ProjetosAndamento'] = Projeto::where('estado_projeto_id', '2')->count();
            $data['ProjetosAvaliacao'] = Projeto::where('estado_projeto_id', '3')->count();
            $data['ProjetosFinalizado'] = Projeto::where('estado_projeto_id', '4')->count();
        } else {
            //   $data['t']=$ta->count();
            $data['projetos'] = Projeto::orderBy('created_at', 'desc')
                ->where('responsavel_id', $responsavel_logado->id)
                ->get();
            $data['ProjetosNiciado'] = Projeto::where('estado_projeto_id', '1')
                ->where('responsavel_id', $responsavel_logado->id)->count();
            $data['ProjetosAndamento'] = Projeto::where('estado_projeto_id', '2')
                ->where('responsavel_id', $responsavel_logado->id)->count();
            $data['ProjetosPendente'] = Projeto::where('estado_projeto_id', '1')
                ->where('responsavel_id', $responsavel_logado->id)->count();
            $data['ProjetosFinalizado'] = Projeto::where('estado_projeto_id', '1')
                ->where('responsavel_id', $responsavel_logado->id)->count();
        }
        $controltarefa = ControlTarefa::where('responsavel_id', $responsavel_logado->id)
            ->select('tarefa_id')
            ->get();
        // verificar se é um administrador ou uma outra função no sistema
        if ($responsavel_logado->funcao->id == 1) {
            $data['tarefasinicial'] = Tarefa::orderBy('data_inicio_real', 'asc')->get();
            // dd($data['tarefas']);
        } else {
            $data['tarefasinicial'] = Tarefa::whereIn('id', $controltarefa)->orderBy('created_at', 'asc')->get();
        }

        // $data->projecto_id;
        // dd($data);

        return Inertia::render('User/EstatisticaProjeto', $data);
    }


    // Tambem trabalhei aqui no dia 09.01.23
    public function estatistica_grafico_projectos(Request $request)
    {
        $admingestor = [1, 2];
        $responsaveis = Pessoa::with('funcao')
            ->whereHas('funcao', function ($query) use ($admingestor) {
                $query->whereNotIn('funcao_id', $admingestor);
            })->get();
        // dd($responsaveis);
        // $responsaveis = Pessoa::get();
        $colecao = collect([]);
        $nome_tarefa_andamento = [];
        $quantidade_tarefa_andamento = [];
        $nome_tarefa_pendente = [];
        $quantidade_tarefa_pendente = [];
        $v = 0;

        foreach ($responsaveis as $responsavel) {
            $v++;
            $quantidade_tarefas_andamento = 0;
            $quantidade_tarefas_pendente = 0;

            $responsavel_tarefa = ControlTarefa::where('responsavel_id', $responsavel->id)->select('tarefa_id')->get();
            // para responsaveis com as tarefas pendente
            $estado = Tarefa::whereIn('id', $responsavel_tarefa)->where('estado_tarefa_id', 1)->get();
            // dd($responsavel_tarefa,$estado);
            $quantidade_tarefas_pendente = count($estado);
            // $data['nome_responsavel_tarefas_pendente'][] = $responsavel->nome_responsavel;
            // $data['quantidade_tarefas_pendente'][] = $quantidade_tarefas_pendente;
            $nome_tarefa_pendente[] = $responsavel->nome_responsavel;
            $quantidade_tarefa_pendente[] = $quantidade_tarefas_pendente;
            $tarefas_pendente = array_combine($nome_tarefa_pendente, $quantidade_tarefa_pendente);
            // para responsaveis  com tarefas em andamento.
            $estado_andamento = Tarefa::whereIn('id', $responsavel_tarefa)->where('estado_tarefa_id', 2)->get();
            $quantidade_tarefas_andamento = count($estado_andamento);

            // Um select para ter o resultado dos responsaveis com as tarefas em andamento
            $data['nome_responsavel_tarefas_andamento'] = $responsavel->nome_responsavel;
            $data['quantidade_tarefas_andamento'] = $quantidade_tarefas_andamento;
            $nome_tarefa_andamento[] = $responsavel->nome_responsavel;
            $quantidade_tarefa_andamento[] = $quantidade_tarefas_andamento;
            $tarefas_andamento = array_combine($nome_tarefa_andamento, $quantidade_tarefa_andamento);
            // arsort($quantidaderesponsavel);

        }
        $projetos = Projeto::orderBy('created_at', 'desc')
            ->withCount('tarefas')->get();
        if ($request->get('estado_projeto_id') > 0) {
            $projetos = Projeto::where('estado_projeto_id', $request->get('estado_projeto_id'))

                ->orderBy('created_at', 'desc')
                ->withCount('tarefas')->get();
        }

        $colecao = collect([]);
        $colecaopercentagem = collect([]);
        $c = 0;
        // $data['labels'] = [];

        $qtp = [];
        $nnomep = [];
        foreach ($projetos as $projeto) {
            $c++;
            $percentagem = 0;
            $andamento = 0;
            $pendente = 0;
            $finalizado = 0;
            // $data['quant'][] = $projeto->tarefas_count;
            // $data['nome_responsavel'][] = $projeto->responsavel->nome_responsavel;
            $quant_tarefa = $projeto->tarefas_count;


            foreach ($projeto->tarefas as $tarefa) {
                // dd($tarefa->estado_tarefa_id);
                $percentagem = $percentagem + $tarefa->percentagem;
                $media_percentagem[] = $quant_tarefa == 0 ? 0 : $percentagem / $quant_tarefa;
                $nome_projecto[] = $projeto->nome_proj;

                // $pendente = 0;
                if ($tarefa->estado_tarefa_id == 1) {
                    // $nomet=$tarefa->nome_tarefa;
                    $pendente += 1;
                    // $colecao->push(['estadotarefa' => $tarefa->estado_tarefa_id]);
                } elseif ($tarefa->estado_tarefa_id == 2) {
                    $andamento += 1;
                } elseif ($tarefa->estado_tarefa_id == 4) {
                    $finalizado += 1;
                }
                // $data['d'][] = $tarefa->estadoTarefa;




            }
            $nomep[] = $projeto->nome_proj;
            $qta[] = $andamento;
            $qtf[] = $finalizado;
            $qtp[] = $pendente;
            $data['ppendente'] = array_combine($nomep, $qtp);
            $data['pandamento'] = array_combine($nomep, $qta);
            $data['pfinalizado'] = array_combine($nomep, $qtf);
            // dd($qtp,$projeto->id,$pendente, $nomep);
            $data['media_percentagem'][] = $quant_tarefa == 0 ? 0 : $percentagem / $quant_tarefa;
            $data['nome_projecto'][] = $projeto->nome_proj;


            // Com o push conseguimos pegar  os dados da consulta e pode comprar direito
            $colecaopercentagem->push([
                'responsavel' => $responsavel->nome_responsavel,
                'quantidade' => $quantidade_tarefas_pendente
            ]);
        }
        $percentagem_nome_projecto = array_combine($nome_projecto, $media_percentagem);
        // dd( $tarefas_andamento ,$colecaopercentagem);
        // dump($data['d']);

        // dd($data['nome_tarefa_pendente_projecto'], $data['total_tarefa_pendente_projecto'],$data['nome_responsavel']);
        // $data['colecaotarefa'] = $colecao->pluck('estado_tarefa_id', 'nome_proj');
        $data['projetos_count'] = $projetos->pluck('tarefas_count', 'nome_proj');
        $data['tarefas_pendente'] = $tarefas_pendente;
        $data['tarefa_andamento'] = $tarefas_andamento;
        // dump($tarefas_pendente,$tarefas_andamento);
        $data['tarefa_p'] = $nome_tarefa_pendente;
        $data['tarefas_pendente'] = $tarefas_pendente;
        $data['percentagem_nome_projecto'] = $percentagem_nome_projecto;
        // dd($data['colecaotarefa'],$pendente);

        $colecao = $tarefa->pluck('estado_tarefa_id');

        // dd( $data['projetos_count'], $data['tarefa_p'] , $data['tarefa_andamento'], $colecao );
        return response()->json($data);
    }

    public function estatistica_grafico_dashboard(Request $request)
    {
        $responsaveis = Pessoa::get();
        $nome_tarefa_andamento = [];
        $quantidade_tarefa_andamento = [];
        $nome_tarefa_pendente = [];
        $quantidade_tarefa_pendente = [];

        $colecao = $responsaveis->pluck('id');

        foreach ($responsaveis as $responsavel) {
            $quantidade_tarefas_andamento = 0;
            $quantidade_tarefas_pendente = 0;

            $responsavel_tarefa = ControlTarefa::where('responsavel_id', $responsavel->id)->select('tarefa_id')->get();
            // para responsaveis com as tarefas pendente
            $estado = Tarefa::whereIn('id', $responsavel_tarefa)->where('estado_tarefa_id', 1)->get();
            $estado_andamento = Tarefa::whereIn('id', $responsavel_tarefa)->where('estado_tarefa_id', 2)->get();
            $quantidade_tarefas_pendente = count($estado);
            $quantidade_tarefas_andamento = count($estado_andamento);
            $nome_tarefa_pendente[] = $responsavel->nome_responsavel;
            $quantidade_tarefa_pendente[] = $quantidade_tarefas_pendente;
            // para responsavei  com tarefas em andamento.


            // Um select para ter o resultado dos responsaveis com as tarefas em andamento
            $nome_tarefa_andamento[] = $responsavel->nome_responsavel;
            $quantidade_tarefa_andamento[] = $quantidade_tarefas_andamento;
            $data['nome_responsavel_tarefas_andamento'][] = $responsavel->nome_responsavel;
            $data['quantidade_tarefas_andamento'][] = $quantidade_tarefas_andamento;
            // array_push($quantidaderesponsavel, $responsavel->nome_responsavel, $quantidade_tarefas_andamento);
            $tarefas_pendente = array_combine($nome_tarefa_pendente, $quantidade_tarefa_pendente);
            $tarefas_andamento = array_combine($nome_tarefa_andamento, $quantidade_tarefa_andamento);
        }
        $collect_tarefa = collect($tarefas_andamento);
        $collect_pendente = collect($tarefas_pendente);

        $data['tarefa_andamento'] = $collect_tarefa;
        $data['tarefa_pendente'] = $collect_pendente;
        $data['colecao'] = $colecao;

        // dd( $data['projetos_count'],  $data['tarefa_andamento']);
        return response()->json($data);
    }
    public function filtrar_grafico_projectos(Request $request)
    {
        $id_estado = $request->get('estado_projeto_id');

        return response()->json($id_estado);
    }
    public function projecto_atualizar(Request $request)
    {


        //try {
        $id = $request->id;
        $data = $request->all();
        $projeto = Projeto::find($id);
        $projeto->update($data);

        if ($request->hasFile('arquivos')) {

            // $file = $request->file('arquivos');
            // Pegar o Id do projecto
            // $projeto = Projeto::find($projeto->id);
            //dd($projeto);
            // Criar uma pasta para adicionaro o arquivo
            $fileName =  $request->arquivos->store('projetos');
            // verificar se o aquivo esxiste nessta pasta se existir apaga
            if ($projeto->arquivos) {
                Storage::delete('projetos', $projeto->arquivos);
            }
            // caso nao existir salva.
            $projeto->arquivos = $fileName;

            $projeto->save();
        } else {
            $projeto->arquivos = null;
        }

        if ($request->hasFile('arquivos_dois')) {

            // $file = $request->file('arquivos');

            $projeto = Projeto::find($projeto->id);
            //dd($projeto);

            $fileName =  $request->arquivos_dois->store('projetos');

            if ($projeto->arquivos_dois) {
                Storage::delete('projetos', $projeto->arquivos_dois);
            }
            $projeto->arquivos_dois = $fileName;
            $projeto->save();
        } else {
            $projeto->arquivos_dois = null;
        }

        if ($request->hasFile('arquivos_tres')) {

            // $file = $request->file('arquivos');

            $projeto = Projeto::find($projeto->id);
            //dd($projeto);

            $fileName =  $request->arquivos_tres->store('projetos');

            if ($projeto->arquivos_tres) {
                Storage::delete('projetos', $projeto->arquivos_tres);
            }

            $projeto->arquivos_tres = $fileName;

            $projeto->save();
        } else {
            $projeto->arquivos_tres = null;
        }

        return redirect()->back()->with('success', 'Atualização feita com sucesso');
        /*   } catch (\Exception $e) {
             dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível realizar a operação');
        }*/
    }
    public function store(Request $request)

    {
        try {
            $nome = $request->nome_proj;
            $data = $request->all();
            $data['estado_projeto_id'] = 1;
            $data['estado_producao_id'] = 2;
            $projeto = Projeto::create($data);
            if ($request->hasFile('arquivos')) {
                // Pegar o Id do projecto
                $projeto = Projeto::find($projeto->id);
                // Criar uma pasta para adicionaro o arquivo
                $fileName =  $request->arquivos->store('projetos');
                // verificar se o aquivo esxiste nessta pasta se existir apaga
                if ($projeto->arquivos) {
                    Storage::delete('projetos', $projeto->arquivos);
                }
                // caso nao existir salva.
                $projeto->arquivos = $fileName;

                $projeto->save();
            } else {
                $projeto->arquivos = null;
            }

            if ($request->hasFile('arquivos_dois')) {
                $projeto = Projeto::find($projeto->id);
                $fileName =  $request->arquivos_dois->store('projetos');

                if ($projeto->arquivos_dois) {
                    Storage::delete('projetos', $projeto->arquivos_dois);
                }
                $projeto->arquivos_dois = $fileName;
                $projeto->save();
            } else {
                $projeto->arquivos_dois = null;
            }
            if ($request->hasFile('arquivos_tres')) {
                $projeto = Projeto::find($projeto->id);

                $fileName =  $request->arquivos_tres->store('projetos');

                if ($projeto->arquivos_tres) {
                    Storage::delete('projetos', $projeto->arquivos_tres);
                }

                $projeto->arquivos_tres = $fileName;

                $projeto->save();
            } else {
                $projeto->arquivos_tres = null;
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível realizar a operação');
        }

        return redirect()->back()->with('success', ' O projecto : ' . $nome . ' Foi registrado com sucesso!');
    }
    public function adicionar_responsavel_projecto(Request $request)
    {

        $projecto = Projeto::findOrFail($request->projecto_id);
        $responsaveis = Pessoa::whereIn('id', $request->get('responsavel_id'))->select('id')->get();

        DB::beginTransaction();

        // notificação
        foreach ($request->get('responsavel_id') as $responsavel) {
            try {
                EquipaProjecto::create([
                    'projecto_id' => $request->projecto_id,
                    'responsavel_id' => $responsavel,
                    'created_by' => auth()->user()->id,
                ]);
            } catch (\Throwable $e) {
                // dd($e->getMessage());
                DB::rollBack();
                return redirect()->back()->with('error', 'Não foi possivel adicionar o responsavel:');
            }
        };

        DB::commit();

        return redirect()->back()->with('success', 'Responsavel adicionado com sucesso!');
    }


    public function  tarefa_concluido(Request $request, $id)
    {
        $tarefa = Tarefa::find($id);
        // dd($tarefa);
        if ($request->get('percentagem') == 100) {
            $data['estado_tarefa_id'] = 4;
        }
        $tarefa->update($data);
        return redirect()->back()->with('success', 'Atualização da percentagem com sucesso');
    }


    public function filtrar_responsavel_projecto(Request $request)
    {
    }
    public function filtrar_estado(Request $request)
    {
        $responsavel = Pessoa::where('user_id', auth()->user()->id)->with('projecto')
            ->first();
        $responsavelprojeto = Projeto::where('responsavel_id', $responsavel->id)
            ->get();
        $data_inicial = $request->get('data_inicial');
        $data_final = $request->get('data_final');
        $projecto_id = $request->get('projecto_id');

        $projeto_id = [];

        if ($responsavel->funcao->id == 1) {
            if ($request->get('projecto_id')) {
                $projeto = Projeto::where('id', $request->get('projecto_id'))->select('id')->get();
            } else {
                $projeto = Projeto::select('id')->get();
            }

            if ($request->get('responsavel_id')) {
                $projeto_id = EquipaProjecto::where('deleted_at', null)->where('responsavel_id', $request->get('responsavel_id'))->select('projecto_id')->get();
                // $projeto_id = Projeto::where('responsavel_id', $request->get('responsavel_id'))->select('id')->get();
                // $responsavel=Pessoa::whereIn('id', $id_responsavel)->select('id')->get();

            } else {
                $projeto_id = Projeto::select('id')->get();
            }

            $estado_projeto = [];
            if ($request->get('estado_projeto_id')) {
                $estado_projeto[] = $request->get('estado_projeto_id');
            } else {
                $estado_projeto = EstadoProjeto::select('id')->get();
            }

            if ($request->get('data_final')) {


                $projetos = Projeto::whereIn('id',  $projeto_id)
                    ->whereIn('estado_projeto_id', $estado_projeto)
                    // ->where('id',  $projeto)
                    ->where('dat_inicio_real', '>=', $data_inicial)
                    ->where('dat_inicio_real', '<=', $data_final)
                    ->orderBy('created_at', 'asc')->get();
                // dd($projetos);
            } else {

                $projetos = Projeto::whereIn('id', $projeto)
                    ->whereIn('id', $projeto_id)
                    ->whereIn('estado_projeto_id', $estado_projeto)
                    ->orderBy('dat_inicio_real', 'asc')
                    ->get();
            }
        } else {
            if ($request->get('responsavel_id')) {

                $projeto_id = EquipaProjecto::where('responsavel_id', $request->get('responsavel_id'))->select('projecto_id')->get();
                // $responsavel=Pessoa::whereIn('id', $id_responsavel)->select('id')->get();

            } else {
                $projeto_id = Projeto::select('id')->get();
            }

            $estado_projeto = [];
            if ($request->get('estado_projeto_id')) {
                $estado_projeto[] = $request->get('estado_projeto_id');
            } else {
                $estado_projeto = EstadoProjeto::select('id')->get();
            }



            if ($request->get('data_final')) {

                $projetos = Projeto::whereIn('id',  $projeto_id)
                    ->whereIn('estado_projeto_id', $estado_projeto)
                    ->where('dat_inicio_real', '>=', $data_inicial)
                    ->where('dat_inicio_real', '<=', $data_final)
                    ->orderBy('created_at', 'asc')->get();
            } else {

                $projetos = Projeto::whereIn('id', $projeto_id)
                    ->whereIn('estado_projeto_id', $estado_projeto)
                    ->orderBy('dat_inicio_real', 'asc')
                    ->get();
            }
        }


        // verificar se é um administrador ou uma outra funçã no sistema
        // dd($request,  $estado_projeto,$projetos);
        return response()->json($projetos);
    }
    public function filtrar_tipo_projectos(Request $request)
    {
        // $data=$request->area_empresa_id;
        $tipo_projecto = TipoProjecto::where('area_empresa_id', $request->area_empresa_id)->get();
        return response()->json($tipo_projecto);
    }

    public function export_projecto_pdf($id_projeto)
    {
        set_time_limit(0);
        $projeto = Projeto::where('id', $id_projeto)->get();

        $pdf = PDF::loadView('arquivo_pdf_projecto', [
            'arquivo_pdf_projecto' => $projeto
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }

    public function listar_pdf_projectos()
    {
        set_time_limit(0);
        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();

        if ($responsavel_logado->funcao->id == 1) {
            $projeto = Projeto::get();
        } else if ($responsavel_logado->funcao->id == 2) {
            // dd($responsavel_logado);
            $projeto = Projeto::where('responsavel_id', $responsavel_logado->id)->orderBy('created_at', 'desc')
                ->get();
        } else {
            $equipa = EquipaProjecto::where('responsavel_id', $responsavel_logado->id)->select('projecto_id')->get();
            $projeto = Projeto::whereIn('id', $equipa)->get();
            // dd($data);

        }
        $pdf = PDF::loadView('pdf_listar_projecto', [
            'pdf_listar_projecto' => $projeto,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }



    public function activar_producao($id)

    {
        Projeto::find($id)->update([
            'estado_producao_id' => 1,
        ]);
    }
    public function desactivar_producao($id)
    {
        Projeto::find($id)->update([
            'estado_producao_id' => 2,
        ]);
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
     * @param  \App\Models\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request  $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {


            $projeto = Projeto::find($id);
            $data = $request->all();
            $projeto->update([
                'nome_proj' => $data['nome_proj'],
                'descricao_proj' => $data['descricao_proj'],
                'prioridade_proj' => $data['prioridade_proj'],
                'dat_inicio_real' => $data['dat_inicio_real'],
                'dat_fim_real' => $data['dat_fim_real'],
                'percetagem_conclusao' => $data['percetagem_conclusao'],
                'arquivos' => $data['arquivos'],
                'arquivos_dois' => $data['arquivos_dois'],
                'arquivos_tres' => $data['arquivos_tres'],
                'estado_projeto_id' => $data['estado_projeto_id'],
                'responsavel_id' => $data['responsavel_id'],
                'updated_at' => $data['updated_at'],
            ]);

            return redirect()->back()->with('success', 'O Projecto : ' . $request->nome_proj . ' Foi atualizado com sucesso');        //code...

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível realizar a operação com sucesso');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $request = Projeto::all();

        $projeto = Projeto::find($id);
        $projeto->update([
            'estado_producao_id' => 2,
        ]);

        // dd($projeto);
        // $projeto->equipaProjecto()->delete();
        // $projeto->tarefas()->delete();
        $projeto->delete();
        return redirect()->back()->with('success', 'O projeto : ' . $projeto->nome_proj . ' foi eliminado com sucesso');
    }
}
