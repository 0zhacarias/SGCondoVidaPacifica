<?php

namespace App\Http\Controllers;

use App\Models\EstadoTarefa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Responsavel;
class EstadoTarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['estadostarefas']=EstadoTarefa::all();
        return Inertia::render('User/EstadoTarefa',$data);
    }

    public function store(Request $request)
    {
        try
        {
            EstadoTarefa::create($request->all());
        return redirect('/tabela_de_apoio/estado_tarefa');
            return redirect()->back()->with('success', 'foi possivel');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'foi possivel');

        }

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


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstadoTarefa  $estadoTarefa
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoTarefa $estadoTarefa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadoTarefa  $estadoTarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoTarefa $estadoTarefa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     *
     */
    public function update(Request $request,$id)
    {
        try
        {

           $estado=EstadoTarefa::find($id);
           $estado->update($request->all());

           return redirect()->back()->with('success', 'foi possivel');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'foi possivel');

        }
        return redirect('/tabela_de_apoio/estado_tarefa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoTarefa  $estadoTarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $funcao=EstadoTarefa::find($id);
        $funcao->delete();
        //
    }
}
