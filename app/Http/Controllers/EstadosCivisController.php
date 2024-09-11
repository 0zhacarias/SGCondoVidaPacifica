<?php

namespace App\Http\Controllers;

use App\Models\EstadosCivis;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Responsavel;

class EstadosCivisController extends Controller
{
    public function index()
    {
        $data['estadoscivis']=EstadosCivis::all();
        return Inertia::render('User/EstadoCivil',$data);
    }

    public function store(Request $request)
    {
        try
        {
            EstadosCivis::create($request ->all());
            // return redirect('/tabela_de_apoio/estado_civil');
            return redirect()->back()->with('success', 'foi possivel');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'foi possivel');

        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
     * Display the specified resource.
     *
     * @param  \App\Models\EstadosCivis  $estadosCivis
     * @return \Illuminate\Http\Response
     */
    public function show(EstadosCivis $estadosCivis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EstadosCivis  $estadosCivis
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadosCivis $estadosCivis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstadosCivis  $estadosCivis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $estado=EstadosCivis::find($id);
        $estado->update($request->all());
            return redirect()->back()->with('success','');

        } catch (\Throwable $th) {
            //throw $th;return
            return redirect()->back()->with('error','');

        }
        return redirect('/tabela_de_apoio/estado_civil');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstadosCivis  $estadosCivis
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadosCivis $estadosCivis)
    {
        //
    }
}
