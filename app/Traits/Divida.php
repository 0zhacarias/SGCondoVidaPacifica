<?php
namespace App\Traits;

use App\Models\Pessoa;

trait Divida
{
    public function pagamentos()
    {
        try {
            $condominos = Pessoa::with(['factura_item.servico', 'factura_item.factura', 'apartamento'])->get();
           
            // Array com os nomes dos meses
            $meses = [
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro'
            ];
            $data = [];
            foreach ($condominos as $condomino) {
                $dataCondomino = [
                    'condomino_id' => $condomino->id,
                    'nome' => $condomino->nome_pessoa,
                    'apartamento' => $condomino->apartamento?->designacao,
                    'servicos' => [],
                ];
                $servicos = $condomino->factura_item->groupBy('servico_id');
                //dd($servicos,$condomino);
                foreach ($servicos as $pagamento => $pagamento_servico) {
                    $dataServico = [
                        'servico' => $pagamento_servico->first()->servico->designacao,
                    ];
                   
                    foreach ($meses as $numero => $nome) {
                        $dataServico[$nome] = 'Não Pago';
                    }
                 $qPagamentos = $pagamento_servico->filter(function ($pag) {
                            return $pag->factura?->estado_factura_id == 2;
                        })->count();
                        for ($i = 1; $i <= $qPagamentos; $i++) {
                            if (isset($meses[$i])) {
                              //  $dataServico[$nome] = 'Pago';
                                $dataServico[$meses[$i]] = 'Pago';
                            }
                        }
                    
                    $dataCondomino['servicos'][] = $dataServico;
                }
                $data[][] = $dataCondomino;
            }
            
            return $data;
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Não foi possivel recuperar os dados', $th->getMessage()]);
        }

    } 
}

