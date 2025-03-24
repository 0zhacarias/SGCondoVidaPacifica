<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Bloco;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pessoa;
use App\Models\Funcoes;
use App\Models\TipoApartamento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;

class BlocoController extends Controller
{
    protected function pessoa()
    {
        return Pessoa::where('user_id', auth()->id())->with('apartamento')->first();
    }
    public function index()
    {
       
        switch ($this->pessoa()['funcao_id']) {
            case 1:
                $data['blocos'] =Bloco::with('sindico')->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $data['blocos'] =Bloco::with('sindico')->where('sindico_id',$this->pessoa()['id'])->orderBy('created_at', 'desc')->get();
                break;

            default:
            return redirect()->route('apartamento.index');
                break;
        }
       
       // dd($data['blocos']);
        $data['contar'] =count($data['blocos']);
        $id = 2;
        $data['gestores'] = Pessoa::where('funcao_id', $id)->get();
        $data['tipologias'] = TipoApartamento::get();
        return Inertia::render('User/Bloco', $data);
    }

    public function projectos_concluido()
    {
        return response()->json($data);
    }

    public function store(Request $request)

    {
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



    public function filtrar_responsavel_projecto(Request $request)
    {
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
