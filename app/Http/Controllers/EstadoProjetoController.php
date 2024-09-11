<?php

namespace App\Http\Controllers;

use App\Models\EstadoProjeto;
use Illuminate\Http\Request;
USE Inertia\Inertia;
use App\Models\Responsavel;

class EstadoProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['estadosprojetos']=EstadoProjeto::all();
        return Inertia::render('User/EstadoProjeto',$data);
    }

    public function store(Request $request)
    {
        try
        {
            EstadoProjeto::create($request->all());
           // return redirect('/tabela_de_apoio/estado_projeto');
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
     * @param  \App\Models\EstadoProjeto  $estadoProjeto
     * @return \Illuminate\Http\Response
     */
    public function show(EstadoProjeto $estadoProjeto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadoProjeto  $estadoProjeto
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoProjeto $estadoProjeto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstadoProjeto  $estadoProjeto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $estado=EstadoProjeto::find($id);
        $estado->update($request->all());
        return redirect()->back()->with('success','cadastro com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadoProjeto  $estadoProjeto
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoProjeto $estadoProjeto)
    {
        //
    }
}
