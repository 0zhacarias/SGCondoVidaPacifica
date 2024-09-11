<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumentoIdentificacao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoDocumentoIdentificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['documentosIdentificacao']=TipoDocumentoIdentificacao::all();
        
        return Inertia::render('documentoIdentificacoes/documentoIdentificacao',$data);
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
    public function store(Request $request)
    {
        TipoDocumentoIdentificacao::create($request->all());
        return redirect('documentoIdentificacoes/documentoIdentificacao');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoDocumentoIdentificacao  $tipoDocumentoIdentificacao
     * @return \Illuminate\Http\Response
     */
    public function show(TipoDocumentoIdentificacao $tipoDocumentoIdentificacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoDocumentoIdentificacao  $tipoDocumentoIdentificacao
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDocumentoIdentificacao $tipoDocumentoIdentificacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoDocumentoIdentificacao  $tipoDocumentoIdentificacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoDocumentoIdentificacao $tipoDocumentoIdentificacao, $id)
    {
        $documentoIdentificacao=TipoDocumentoIdentificacao::find($id);
        $documentoIdentificacao->update($request->all());
        return redirect('documentoIdentificacoes/documentoIdentificacoes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoDocumentoIdentificacao  $tipoDocumentoIdentificacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data =$documentoIdentificacao=TipoDocumentoIdentificacao::find($id);
        $documentoIdentificacao->delete();
    }
}
