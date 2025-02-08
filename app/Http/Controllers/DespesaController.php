<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\DespesasItens;
use App\Models\Factura;
use App\Models\Financa;
use App\Models\Pessoa;
use App\Models\Servico;
use App\Traits\Divida;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDF;

class DespesaController extends Controller
{
    use Divida;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['servicos'] = Servico::get();
        $data['despesas'] = Despesa::with('despesas_item')->get();

        return Inertia('User/Despesas', $data);
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
        // dd(request());
        DB::beginTransaction();

        $pessoa = Pessoa::where('user_id', auth()->id())->with('apartamento')->first();
        // dd($pessoa->apartamento->id,count( request()->servicos));

        $despesa = Despesa::create([
            'faturaReference' => 'DESP-' . date('Y') . count(Despesa::get()) + 1,
            'total_geral' => $request->input('total_preco_factura'),
            'apartamento_id' => $pessoa->apartamento->id,
            'valor_depositado' => $request->input('valor_depositado'),
            'created_by' => auth()->id(),
            'estado_factura_id' => 5,
            'data_vencimento' => date('Y-m-d'),

        ]);
        $servicos = request()->servicos;
        $iva = 0.14;
        $total_geral = 0;
        $valor_depositado = 0;

        $nomeitens = null;
        foreach ($servicos as $servico) {
            $total_geral = $total_geral + $servico['total_g'];
            $valor_depositado = $valor_depositado + $servico['preco'];
            $nomeitens = $servico['designacao'] . " & " . $nomeitens;
            DespesasItens::create([
                'factura_id' => $despesa->id,
                'quantidade' => $servico['quantidade'],
                'preco' => $servico['preco'],
                // 'preco'=>number_format($servico['preco'], 2, ',', '.'),
                'designacao' => $servico['designacao'],

                'total' => $servico['total_g'],
            ]);
        }
        $descricao = "Saida dos produtos " . $nomeitens;
        $despesa->descricao = $descricao;
        $despesa->observacao = $descricao;
        $despesa->total_preco_factura = $total_geral;
        $despesa->valor_a_pagar = $total_geral + ($total_geral * $iva);
        $despesa->total_iva = $total_geral * $iva;
        $despesa->save();
        DB::commit();
        return ['despesa_id' => $despesa->id];
        DB::rollBack();
    }

    public function relatorio_despesas($id)
    {
        $despesa = Despesa::with('despesas_item', 'estado_fatura', 'apartamento', 'pessoa')->find($id);
        //dd($apartamento);
        $pdf = PDF::loadView('despesas_pdf', [
            'factura' => $despesa
        ]);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    public function inadeplencia(Request $request)
    {
        $resultado = [];

        foreach ($this->pagamentos()  as $item) {
            // Cada item é um array contendo um único array no índice 0
            $condomino = $item[0];
            
            // Adicionar ao array final
            $resultado[] = [
                'condomino_id' => $condomino['condomino_id'],
                'nome' => $condomino['nome'],
                'servicos' => $condomino['servicos'],
            ];
        }

       /*  $pagamentos = [];
        foreach($this->pagamentos() as $pagamento){
            $pagamentos=$pagamento;
        }
        dd($resultado,$item[0],$this->pagamentos()); */
      /*  return view('inadeplencia_pdf', [
            'variavel' => $resultado,
        ]);*/
        //dd($this->pagamentos()->toArray());
         $pdf = PDF::loadView('inadeplencia_pdf', [
            'variavel' => $resultado,
        ])->setPaper('a4', 'landscape');
        return $pdf->stream(); 
        // return $pdf->download('inadeplencia.pdf');
    }
    public function caixa(Request $request)
    {
        $saidas = Despesa::where('estado_factura_id',2)->sum('valor_a_pagar');
        $pagamentos=Factura::where('estado_factura_id',2)->sum('valor_a_pagar');
        $pagamentos_gerado=Factura::where('estado_factura_id',5)->sum('valor_a_pagar');
        //dd($entradas);
        $pdf=pdf::loadview('fluxo_caixa_pdf', [
            'saidas' => $saidas,
            'pagamentos' => $pagamentos,
            'ppagar' => $pagamentos_gerado,
        ]);
        return $pdf->stream();
    }
    public function balancete(Request $request)
    {
       // dd($request);
        $saidas = Despesa::where('estado_factura_id',2)->sum('valor_a_pagar');
        $pagamentos=Factura::where('estado_factura_id',2)->sum('valor_a_pagar');
        //dd($entradas);
        return view('balancete_pdf', [
            'saidas' => $saidas,
            'pagamentos' => $pagamentos,
        ]);
        return $pdf->stream();
        //return $pdf->download('users.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function show(Despesa $despesa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function edit(Despesa $despesa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Despesa $despesa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Despesa $despesa)
    {
        //
    }
}
