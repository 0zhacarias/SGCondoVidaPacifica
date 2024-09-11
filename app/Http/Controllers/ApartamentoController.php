<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ControlTarefa;
use App\Models\EquipaProjecto;
use App\Models\EstadoTarefa;
use App\Models\Pessoa;
use App\Models\TipoApartamento;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\Apartamento;
use App\Models\Bloco;
use Illuminate\Support\Facades\DB;

class ApartamentoController extends Controller
{
    protected $modelUser;
    public function __construct()
    {
        $this->modelUser = new User;
    }

    public function index()

    {
        try {
            $data['todos_apartamentos'] = Apartamento::with('condomino','tipo_apartamento','estado_apartamento')->get();
            return Inertia::render('User/Apartamento', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function  bloco_apartamento($id)
    {
        $id = base64_decode(base64_decode(base64_decode($id)));
        try {
            $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->first();
     
            if ($responsavel_logado->funcao->id == 1 || $responsavel_logado->funcao->id == 2) {

                $data['tipos_apartamento'] = TipoApartamento::orderBy('created_at', 'desc')->get();
                $data['apartamentos'] = Apartamento::with('condomino','tipo_apartamento','estado_apartamento')->get();
                $sindico_id=Bloco::find($id)->pluck('sindico_id')->first();
               // dd($sindico_id);
$data['sindicos']=Pessoa::with('apartamento','genero')->where('id',$sindico_id)->get();
                //dd( $data['apartamentos'],$sindicos);
                $data['projetos'] = Bloco::all();
                $data['condominos'] = Pessoa::with('funcao')->get();
            } else {
                $data['tipos_apartamento'] = Apartamento::whereIn('created_by', $responsavel_logado)->orderBy('created_at', 'desc')->get();
                $data['apartamentos'] = Apartamento::whereIn('created_by', $responsavel_logado)->orderBy('created_at', 'desc')->get();

            }

           
            return Inertia::render('User/Apartamento',$data);
        } catch (\Exception $e) {
            dd($e->getMessage());
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
    public function store(Request $request)
    {

        try {
            //dd(request());
            DB::beginTransaction();
           /*  $data_aux = strtotime($request->get('data_inicio_real') . "+" . $request->get('tempo_execucao') . "days");
            $data_termino = date("Y-m-d H:i:s", $data_aux); */
            $data = request()->all();
            $data['estado_apartamento_id']=1;
            $data['condomino_id']=implode(request()->condomino_id);

            $apartamento = Apartamento::create($data);
            DB::commit();
            // Mail::to('flaviodecarvalho6@gmail.com')->send(new notificar_tarefa($vtarefa->nome_tarefa, $vtarefa->descricao, $vtarefa->data_inicio_real, $vtarefa->tempo_execucao, $vtarefa->data_fim_real, $vtarefa->percentagem, $nomecriadoPor, $nomeProjecto, $nomeEstado, $url));
            return redirect()->back()->with('success', 'Foi cadastra com sucesso a apartamento');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()->back()->with('error', 'Não foi possivel cadastrar essa apartamento');
        }
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
             dd($e->getMessage());
            // DB::commit();
            return redirect()->back()->with('error', 'Não foi possivel cadastrar essa apartamento');
            //    return redirect('/tarefas/apartamento');
            //throw $th;
        }

        $vtarefa = Apartamento::where('id', $request->tarefa_id)->first();
        $projecto = Bloco::where('id', $vtarefa->projecto_id)->first();
        $nomeProjecto = $projecto->nome_proj;
        $estado = EstadoTarefa::where('id', $vtarefa->estado_tarefa_id)->first();
        $nomeEstado = $estado->designacao;
        $url = action([TarefaController::class, 'index']);
        $criadoPor = User::where('id', $vtarefa->created_by)->first();
        $nomecriadoPor = $criadoPor->name;

        /* dd($vtarefa); */

        //Mail::to('flaviodecarvalho6@gmail.com')->send(new notificar_tarefa($vtarefa->nome_tarefa, $vtarefa->descricao, $vtarefa->data_inicio_real, $vtarefa->tempo_execucao, $vtarefa->data_fim_real, $vtarefa->percentagem, $nomecriadoPor, $nomeProjecto, $nomeEstado, $url));

        return redirect()->back()->with('success', 'Foi cadastra com sucesso a apartamento');
    }

    public function  aceitar_tarefa(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return redirect()->back()->with('success', 'A apartamento foi aceita');
        } catch (\Throwable $th) {
            db::rollBack();
            return redirect()->back()->with('error', 'Não foi possivel atualizar está apartamento ');
        }
    }


    public function rejeitar_tarefa(Request $request, $id)

    {

        try {

            $id_responsavel = Pessoa::where('user_id', $id)->select('id')->first();
            $apartamento = Apartamento::find($id);
            $apartamento->update([
                'estado_tarefa_id' => 6,
                'motivo_rejeicao' => $request->motivo_rejeicao,
                'rejeitado_by' => auth()->user()->id,
                'data_rejeicao' => date("Y-m-d"),

            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possível rejeitar essa apartamento', $th->getMessage());
        }

        return redirect()->back()->with('success', 'Essa apartamento foi rejeitada!');
    }

    public function  cancelamento_tarefa(Request $request, $id)
    {
        try {
            $apartamento = Apartamento::find($id);
            $motivo =  "Aviso! " . $apartamento->nome_tarefa . " Foi cancelada ";
            $apartamento->update([
                'estado_tarefa_id' => 7,
                'motivo_cancelamento' => $motivo,
                'data_cancelamento' => date('Y-m-d'),
                'cancelado_by' => auth()->user()->id
            ],);

            return redirect()->back()->with('success', 'A apartamento foi Cancelada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não é possivel cancelar está apartamento');
        }
    }


    public function  tarefa_concluido(Request $request, $id)
    {
        try {
            $apartamento = Apartamento::find($id);
            $percentagem = $request->get('percentagem');

            //dd($apartamento->estado_tarefa_id);
            if ($percentagem == 100 || $apartamento->estado_tarefa_id == 5) {
                $data['estado_tarefa_id'] = 4;
                $data['percentagem'] = 100;
            }


            $apartamento->update($data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'A percentagem não está ' . $percentagem . '% e deve estar à 100% concluida');
        }

        return redirect()->back()->with('success', 'Atualização da percentagem com sucesso');
    }

    public function  atualizar_percentagem_tarefa(Request $request, $id)
    {

        try {
            $apartamento = Apartamento::find($id);

            return redirect()->back()->with('success', 'Atualização da percentagem com sucesso');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possivel é ditar a percentagem de vido a alguma informação incorreta');

        }
    }


    public function add_tarefa_responsavel(Request $request)
    {

        try {
            return redirect()->back()->with('sucess', 'Foi cadastra com sucesso a apartamento');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possivel');
        }
    }
    public function listar_tarefa($id)
    {
        $id = base64_decode(base64_decode(base64_decode($id)));
DB::beginTransaction();
DB::commit();
DB::rollBack();
        try {

        } catch (\Exception $e) {
        }
    }


    public function filtrar_estado(Request $request)
    {
        return response()->json($request);
    }
    public function filtrar_tarefa_responsavel(Request $request)
    {
 try{

 }
 catch(\Exception $ex){
    
 }
        return response()->json($request);
    }



    public function export_tarefa_pdf($id_tarefa)
    {
        $apartamento = Apartamento::where('id', $id_tarefa)->get();
        $pdf = PDF::loadView('arquivo_pdf_tarefa', [
            'arquivo_pdf_tarefa' => $apartamento
        ]);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    public function listar_pdf_tarefas($id)
    {
        set_time_limit(1024);

        if (is_numeric($id)) {
            $apartamento = Apartamento::where('projecto_id', $id)->get();
        } else {
        }
        $pdf = PDF::loadView('pdf_listar_tarefa', [
            'pdf_listar_tarefa' => $apartamento,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    public function listar_meus_pdf_tarefas($id)
    {
        set_time_limit(0);

        $pdf = PDF::loadView('pdf_listar_tarefa', [
            'pdf_listar_tarefa' => $apartamento,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
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
        DB::beginTransaction();
DB::commit();
DB::rollBack();
        try {
            $apartamento = Apartamento::find($id);
            $estado_tarefa_id = $apartamento->estado_tarefa_id;
            $data_aux = strtotime($request->get('data_inicio_real') . "+" . $request->get('tempo_execucao') . "days");
            $data_termino = date("Y-m-d H:i:s", $data_aux);
            $data = $request->all();
            if ($data_termino > $apartamento->data_fim_real && ($apartamento->estado_tarefa_id == 5 || $apartamento->estado_tarefa_id == 6)) {
                $estado_tarefa_id = 2;
            }
            $apartamento->update([
                'nome_tarefa' => $request['nome_tarefa'],
                'descricao' => $request['descricao'],
                'data_inicio_real' => $request['data_inicio_real'],
                'estado_tarefa_id' => $estado_tarefa_id,
                'data_fim_real' => $data_termino,
                'tempo_execucao' => $request->get('tempo_execucao'),
                'projecto_id' => $request->get('projecto_id'),
                'updated_by' =>  auth()->user()->id,
            ]);
            $responsaveis = $request->get('responsavel_id');
            if (isset($responsaveis)) {
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
        $apartamento = Apartamento::find($id);
        $apartamento->controlTarefas()->delete();
        $apartamento->delete();
        return redirect()->back()->with('success', 'Dados eliminado com sucesso');
    }
}
