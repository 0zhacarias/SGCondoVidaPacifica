<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Bloco;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pessoa;
use App\Models\Funcoes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;

class BlocoController extends Controller
{
    public function index()
    {

        $data['blocos'] =Bloco::with('sindico')->orderBy('created_at', 'desc')->get();
       // dd($data['blocos']);
        $data['contar'] =count($data['blocos']);
        $id = [2];
        $data['gestores'] = Pessoa::whereIn('funcao_id', $id)->get();
        return Inertia::render('User/Bloco', $data);
    }

    public function projectos_concluido()
    {
        return response()->json($data);
    }

    public function store(Request $request)

    {
       // dd($request);
        try {
            $projeto=New Bloco;
            $projeto->designacao =request()->designacao ; 
            $projeto->descricao_bloco =request()->descricao_bloco ; 
            $projeto->estado_bloco_id =1 ; 
            $projeto->sindico_id=request()->sindico_id ; 
            $projeto->numero_apartamento=request()->numero_apartamento ; 
            if ($request->hasFile('arquivos')) {
                $fileName =  $request->arquivos->store('projetos');
                if ($projeto->arquivos) {
                    Storage::delete('Blocos', $projeto->arquivos);
                }
                $projeto->imagem = $fileName;              
            } else {
                $projeto->imagem = null;
            }

            $projeto->save();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível realizar a operação');
        }

        return redirect()->back()->with('success', ' O Bloco Foi registrado com sucesso!');
    }
    public function adicionar_sindico(Request $request)
    {

        $projecto =Bloco::findOrFail($request->projecto_id);
        $responsaveis = Pessoa::whereIn('id', $request->get('responsavel_id'))->select('id')->get();
        DB::beginTransaction();
        foreach ($request->get('responsavel_id') as $responsavel) {
            try {
                EquipaProjecto::create([
                    'projecto_id' => $request->projecto_id,
                    'responsavel_id' => $responsavel,
                    'created_by' => auth()->user()->id,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Não foi possivel adicionar o responsavel:');
            }
        };

        DB::commit();

        return redirect()->back()->with('success', 'Responsavel adicionado com sucesso!');
    }


    public function  tarefa_concluido(Request $request, $id)
    {
        $tarefa = Apartamento::find($id);
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
       
        return response()->json($projetos);
    }

    public function bloco_pdf($id_projeto)
    {
        set_time_limit(0);
        $projeto =Bloco::where('id', $id_projeto)->get();

        $pdf = PDF::loadView('arquivo_pdf_projecto', [
            'arquivo_pdf_projecto' => $projeto
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }

    public function blocos_pdf()
    {
        set_time_limit(0);
        $responsavel_logado = Pessoa::where('user_id', auth()->user()->id)->with('funcao')->first();
        if ($responsavel_logado->funcao->id == 1) {
            $projeto =Bloco::get();
        } else if ($responsavel_logado->funcao->id == 2) {

        } else {
        }
        $pdf = PDF::loadView('pdf_listar_projecto', [
            'pdf_listar_projecto' => $projeto,
            'datatime' => date("Y-m-d"),
        ]);
        // dd($projeto);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
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


            $projeto =Bloco::find($id);
            $projeto->update([
                'nome_proj' => $request['nome_proj'],

            ]);

            return redirect()->back()->with('success', 'O Projecto : ' . $request->nome_proj . ' Foi atualizado com sucesso');        //code...

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível realizar a operação com sucesso');
        }
    }
    public function destroy($id)
    {
 
        $projeto =Bloco::find($id);
        $projeto->update([
            'estado_producao_id' => 2,
        ]);
        $projeto->delete();
        return redirect()->back()->with('success', 'O projeto : ' . $projeto->nome_proj . ' foi eliminado com sucesso');
    }
}
