<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Apartamento;
use App\Models\Bloco;
use App\Models\ControlTarefa;
use App\Models\Pessoa;

class DashboardController extends Controller

{
   public function index()
   {
      $responsavel_logado = Pessoa::where('user_id', auth()
         ->user()->id)
         ->with('funcao')->first();

      if ($responsavel_logado->funcao->id == 1) {
         $data['apartamentos'] = Apartamento::count();
         $data['condominos'] = Pessoa::count();
         $data['mesesNPago'] = Apartamento::count();
         $data['valorPago'] = Apartamento::count();
         $data['condominosSemDividas'] = Pessoa::limit(5)->get();
      } else {
         $data['apartamentos'] = Apartamento::count();
         $data['condominos'] = Pessoa::count();
         $data['mesesNPago'] = Apartamento::count();
         $data['valorPago'] = Apartamento::count();
         $data['condominosSemDividas'] = Pessoa::limit(5)->get();
      }
      return Inertia::render('Dashboard', $data);
}
 /*   public function grafico_responsaveis_tarefa_dashboard(Request $request)
   {
      $admingestor=[1,2];
        $responsaveis=Pessoa::with('funcao')
        ->whereHas('funcao', function ($query) use ($admingestor) {
            $query->whereNotIn('funcao_id', $admingestor);
        })->get();
       $nome_tarefa_andamento = [];
       $quantidade_tarefa_andamento = [];
       $nome_tarefa_pendente = [];
       $quantidade_tarefa_pendente = [];

       $colecao = $responsaveis->pluck('id');

       foreach ($responsaveis as $responsavel) {
           $quantidade_tarefas_andamento = 0;
         //   $quantidade_tarefas_pendente = 0;
                     $responsavel_tarefa = ControlTarefa::where('responsavel_id', $responsavel->id)->select('tarefa_id')->get();
           // para responsaveis com as tarefas pendente
           $estado_andamento = Apartamento::where('estado_tarefa_id', '4')->whereIn('id', $responsavel_tarefa)->get();
           $quantidade_tarefas_andamento = count($estado_andamento);

           // para responsavei  com tarefas em andamento.
           // Um select para ter o resultado dos responsaveis com as tarefas em andamento
           $nome_tarefa_andamento[] = $responsavel->nome_responsavel;
           $quantidade_tarefa_andamento[] = $quantidade_tarefas_andamento;
           $tarefas_finalizado = array_combine($nome_tarefa_andamento, $quantidade_tarefa_andamento);
           arsort($tarefas_finalizado);


         $quantidade_tarefas_pendente = 0;
           $responsavel_tarefa = ControlTarefa::where('responsavel_id', $responsavel->id)->select('tarefa_id')->get();
           // para responsaveis com as tarefas pendente
           $estado = Apartamento::whereIn('id', $responsavel_tarefa)->where('estado_tarefa_id', 1)->get();
           $quantidade_tarefas_pendente = count($estado);
         //   dd($quantidade_tarefas_andamento,  $nome_tarefa_andamento,$quantidade_tarefa_andamento,$estado );
           $nome_tarefa_pendente[] = $responsavel->nome_responsavel;
           $quantidade_tarefa_pendente[] = $quantidade_tarefas_pendente;
           // Um select para ter o resultado dos responsaveis com as tarefas em andamento
         //   $nome_tarefa_andamento[] = $responsavel->nome_responsavel;
         //   $quantidade_tarefa_pendente[] = $quantidade_tarefas_pendente;
           // array_push($quantidaderesponsavel, $responsavel->nome_responsavel, $quantidade_tarefas_andamento);
           $tarefas_pendente = array_combine($nome_tarefa_pendente, $quantidade_tarefa_pendente);
           arsort( $tarefas_pendente);

       }
       $collect_tarefa = collect($tarefas_finalizado);
       $data['membros_quantidade_tarefa_finalizado'] = $tarefas_finalizado;
       $collect_pendente = collect($tarefas_pendente);
       $data['membros_quantidade_tarefa_pendente'] = $tarefas_pendente;

         // dd($quantidade_tarefa_andamento,$quantidade_tarefa_pendente);
      //  dd(  $tarefas_andamento,$tarefas_pendente);
       return response()->json($data);
   } */
}
