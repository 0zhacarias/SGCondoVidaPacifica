<?php

namespace App\Http\Controllers;

use App\Models\Apartamento;
use App\Models\Bloco;
use App\Models\Despesa;
use App\Models\Financa;
use App\Models\Servico;
use App\Models\Factura;
use App\Models\Pessoa;
use App\Models\FacturaItem;
use App\Models\FormaPagamento;
use Illuminate\Support\Facades\DB;
use PDF;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FinancaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function pessoa()
    {
        return Pessoa::where('user_id', auth()->id())->with('apartamento')->first();
    }
    public function index()
    {
        $data['blocos'] = Bloco::get();
        $data['condominos'] = Pessoa::get();
        $data['apartamentos'] = Apartamento::get();
        return Inertia('User/Financas', $data); //
    }
    public function despesa_index()
    {
        return Inertia('User/FinancasPagamento'); //
    }
    public function pagamento_index()
    {
        switch ($this->pessoa()['funcao_id']) {
            case 1:
                $data['facturas'] = Factura::with('estado_fatura', 'apartamento', 'pessoa')
                    ->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $apartamento_id = Apartamento::whereHas('bloco', function ($query) {
                    $query->where('sindico_id', $this->pessoa()['id']);
                })->pluck('id');
                $data['facturas'] = Factura::whereIn('apartamento_id', $apartamento_id)->with('estado_fatura', 'apartamento', 'pessoa')
                    ->orderBy('created_at', 'desc')->get();
                break;

            default:
                $data['facturas'] = Factura::where('created_by', $this->pessoa()['id'])->with('estado_fatura', 'apartamento', 'pessoa')
                    ->orderBy('created_at', 'desc')->get();
                break;
        }


      //  dd($data['facturas']);


        $data['forma_pagamentos'] = FormaPagamento::get();

        return Inertia('User/Pagamentos', $data); //
    }
    public function factura()
    {
        $data['servicos'] = Servico::get();

        return Inertia('User/Factura', $data);
    }
    public function emitir_factura(Request $request)
    {
        dd($request);
    }
    public function crear_despesa(Request $request)
    {
        try {

            Servico::create($request->all());
            return redirect()->back()->with('success', 'Serviço cadastrado com ssucesso');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Não foi possivel cadastra o serviõ', $th->getMessage());
        }
        //dd($request);
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
        DB::beginTransaction();
        //dd(request());
        $pessoa = Pessoa::where('user_id', auth()->id())->with('apartamento')->first();
        // dd($pessoa->apartamento->id,count( request()->servicos));
        $descricao = "Emissão de facturas para os serviços do condominio";
        $factura = Factura::create([
            'descricao' => $descricao,
            'observacao' => $descricao,
            'faturaReference' => 'FSC-' . date('Y') . count(Factura::get()) + 1,
            'total_geral' => $request->input('total_preco_factura'),
            'apartamento_id' => $pessoa->apartamento->id,
            'valor_depositado' => $request->input('valor_depositado'),
            'created_by' => $this->pessoa()['id'],
            'estado_factura_id' => 5,
            'data_vencimento' => date('Y-m-d'),

        ]);
        $servicos = request()->servicos;
        // dd($servicos);
        $iva = 0.14;
        $total_iva = $iva * count($servicos);
        $total_geral = 0;
        $valor_depositado = 0;


        foreach ($servicos as $servico) {
            $total_geral = $total_geral + $servico['total_g'];
            $valor_depositado = $valor_depositado + $servico['preco'];

            FacturaItem::create([
                'factura_id' => $factura->id,
                'servico_id' => $servico['id'],
                'quantidade' => $servico['quantidade'],
                'preco' => $servico['preco'],
                'designacao' => $servico['designacao'],
                'total' => $servico['total_g'],
                'created_by' => $this->pessoa()['user_id'],
            ]);
        }
        //dd($total_geral,$valor_depositado);
        $factura->total_preco_factura = $total_geral;
        $factura->valor_a_pagar = $total_geral + ($total_geral * $iva);
        $factura->total_iva = $total_geral * $iva;
        $factura->save();
        DB::commit();
        return ['factura_id' => $factura->id];
        DB::rollBack();
    }
    public function relatorio_factura($id)
    {
        //dd($apartamento);
        $factura = Factura::with('factura_itens', 'estado_fatura', 'apartamento', 'pessoa')->find($id);
        $pdf = PDF::loadView('factura_pdf', [
            'factura' => $factura
        ]);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Financa  $financa
     * @return \Illuminate\Http\Response
     */
    public function show(Financa $financa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Financa  $financa
     * @return \Illuminate\Http\Response
     */
    public function edit(Financa $financa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Financa  $financa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Financa $financa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Financa  $financa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factura = Factura::find($id);
        $factura->delete();
    }
}
