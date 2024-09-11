<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Factura;
use App\Models\FacturaItem;
use App\Models\ImagemPagamento;
use App\Models\Servico;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //$imagems_pagamento=$request->files->get('processar')['imagem_pagamento'];

        $factura = Factura::find($request->input('pagamento_faturas')['id']);
        $factura->estado_factura_id = 1;
        $factura->update();
        $pagamento = Pagamento::create([
            'factura_id' => $request->input('pagamento_faturas')['id'],
            'observacao' => $request->input('pagamento_faturas')['descricao'],
            'referenciaFactura' => $request->input('pagamento_faturas')['faturaReference'],
            'total_geral' => $request->input('pagamento_faturas')['total_preco_factura'],
            'valor_extenso' => $request->input('pagamento_faturas')['total_preco_factura'],
            'nome_banco' => $request->input('nome_banco'),
            'forma_pagamento_id' => $request->input('forma_pagamento_id'),
            'valor_depositado' => $request->input('valor_depositado'),
            'created_by' => auth()->id(),
            'estado_pagamento_id' => 1,
            'data_pago_banco' => date('Y-m-d'),

        ]);
        if ($request->hasFile('imagem_pagamento')) {
            foreach ($request->imagem_pagamento as $index => $imagem) {
                if ($imagem->isValid()) {
                    $caminho_img = $imagem->store('Comprovativos');
                } else {
                    return ['error' => 'Conprovativo obrigatório!!'];
                }
                ImagemPagamento::create([
                    'designacao' => 'Comprovativo do pagamento da fatura nº : ' . $request->input('pagamento_faturas')['faturaReference'],
                    'imagem_pagamento' => $caminho_img,
                    'pagamento_id' => $pagamento->id,
                ]);
            }
        }
    }
    function imagens_pagamentos()
    {
        $data['pagamento_feito'] = Pagamento::where('factura_id', request()->factura_id)->first();
        $data['imagens'] = ImagemPagamento::where('pagamento_id', $data['pagamento_feito']->id)->get();
        return response()->json($data);
        //dd(request()->factura_id, $pagamento_id, $imagens);
    }
    function validar_pagamento(Factura $id)
    {
        $id->estado_factura_id = 2;
        $id->save();
        //dd(request(), $id);
    }
    function despesas()
    {
        // Definição dos meses com suas descrições e números
$meses = [
    ['nome' => 'Janeiro', 'numero' => 1],
    ['nome' => 'Fevereiro', 'numero' => 2],
    ['nome' => 'Março', 'numero' => 3],
    ['nome' => 'Abril', 'numero' => 4],
    ['nome' => 'Maio', 'numero' => 5],
    ['nome' => 'Junho', 'numero' => 6],
    ['nome' => 'Julho', 'numero' => 7],
    ['nome' => 'Agosto', 'numero' => 8],
    ['nome' => 'Setembro', 'numero' => 9],
    ['nome' => 'Outubro', 'numero' => 10],
    ['nome' => 'Novembro', 'numero' => 11],
    ['nome' => 'Dezembro', 'numero' => 12]
];

// Inicializa o array com todos os meses e uma descrição padrão
$arrayObjetos = [];
foreach ($meses as $mes) {
    $arrayObjetos[$mes['numero']] = [
        'mes' => $mes['nome'],
        'servicos' => [
        ]
    ];
}
$anoAtual = now()->year; // ou use um valor fixo se necessário

foreach ($meses as $mes) {
    $numero = $mes['numero'];

    // Obtém os serviços para o mês e ano específico
    $todosServicos = Servico::all(); // Supondo que você tem um modelo para Serviços
    foreach ($todosServicos as $todos) {
        $servesExiste = FacturaItem::where('servico_id', $todos->id)
            ->where('created_by', auth()->id())
            //->whereYear('created_at', $anoAtual)
            ->whereMonth('created_at', $numero)
            ->pluck('created_by')
            ->first();

        if ($servesExiste) {
            $hasData = true;

            $objeto = (object) [
                'mes' => $mes['nome'],
                'servicos' => $todos->designacao,
                'created_by' => $servesExiste,
            ];

            // Atualiza o arrayObjetos com os dados reais
            $arrayObjetos[$numero]['servicos'][] = [
                'descricao' => $objeto->servicos,
                'created_by' => $objeto->created_by
            ];
        }
        else {
            // Adiciona o serviço com uma descrição de 'Nenhum dado disponível'
            $arrayObjetos[$numero]['servicos'][] = [
                'descricao' => $todos->designacao . ' - Nenhum dado disponível',
                'created_by' => 'zero'
            ];
        }
    }
}

// Monta a estrutura final de dados
$data = [
    'items' => array_values($arrayObjetos)
];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamento $pagamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Pagamento $pagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pagamento  $pagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamento $pagamento)
    {
        //
    }
}
