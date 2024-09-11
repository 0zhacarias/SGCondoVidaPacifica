<?php

use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\FinancaController;
use App\Http\Controllers\PagamentoController;
use App\Mail\EnviarMail;
use App\Models\Apartamento;
use App\Models\Financa;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/utilizador', function () {
  return Inertia::render('User/User');
});

Route::get('/utilizador', function () {
  return view('auth/autenticar');
});*/

//Routa raiz da cara principal do sistema
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

//Gupo de routas do dashboard após o login

//Rotas do SIGCOND

Route::group(['middleware'=>'auth', 'prefix'=>'financas'], function(){
    Route::get('despesa',[DespesaController::class,'index']);
    Route::post('/despesas-saida',[DespesaController::class,'store'])->name('saida');
    
Route::get('/index',[FinancaController::class,'index' ])->name('financa.index');
Route::get('/pagamentos',[FinancaController::class,'despesa_index' ])->name('despesas.index');
Route::get('/factura_pagamentos',[FinancaController::class,'pagamento_index' ])->name('pagamento.index');
Route::get('/factura',[FinancaController::class,'factura']);
Route::post('/emitir-factura',[FinancaController::class,'store'])->name('emitir_factura');
Route::delete('/eliminar-factura/{id}',[FinancaController::class,'destroy'])->name('eliminar_pagamento');
Route::post('/adicionar-pagamento',[PagamentoController::class,'store'])->name('adicionar_pagamento');
Route::get('/despesas',[PagamentoController::class,'despesas'])->name('despesas');
Route::put('/validar-pagamento/{id}',[PagamentoController::class,'validar_pagamento'])->name('validar_pagamento');
Route::post('/imagens-pagamentos',[PagamentoController::class,'imagens_pagamentos'])->name('imagens_pagamentos');
Route::delete('/anular-pagamento',[PagamentoController::class,'destroy'])->name('eliminar_pagamento');

});
Route::group(['middleware'=>'auth', 'prefix'=>'relatorios'],function(){
    Route::get('/factura/{id}',[FinancaController::class,'relatorio_factura'])->name('relatorio_factura');
    Route::get('/despesas/{id}',[DespesaController::class,'relatorio_despesas'])->name('relatorio_despesas');
    Route::get('/inadeplencia',[DespesaController::class,'inadeplencia'])->name('relatorio_inadeplencia');
    Route::get('/balancete',[DespesaController::class,'balancete'])->name('relatorio_balancete');
    Route::get('/caixa',[DespesaController::class,'caixa'])->name('relatorio_caixa');
});


Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('MenuIncial');
    Route::get('/dashboard',[App\Http\Controllers\DashboardController::class, 'dashboard']);
    Route::get('/grafico-responsaveis-tarefa-dashboard', [App\Http\Controllers\DashboardController::class, 'grafico_responsaveis_tarefa_dashboard']);
    Route::get('/projectos-departamento-dasboard', [App\Http\Controllers\DashboardController::class, 'projectos_departamento_dasboard']);
    Route::get('/percentagem-estados-projectos', [App\Http\Controllers\DashboardController::class, 'percentagem_estados_projectos']);
    Route::get('/estatistica-grafico-pendente', [App\Http\Controllers\DashboardController::class, 'estatistica_grafico_pendente']);
    Route::get('/atualizar-tarefas-atrasada', [App\Http\Controllers\ApartamentoController::class, 'atualizar_tarefa_atrasada']);


});
Route::group(['prefix'=>'apartamentos','middleware'=>'auth'],function(){
Route::get('apartamento',[ApartamentoController::class,'index']);
});
Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::get('/perfils', [\App\Http\Controllers\UserController::class, 'perfil'])->name('perfil');
    Route::put('atualizar-senha/{id}', [\App\Http\Controllers\UserController::class, 'atualizar_senha']);
    Route::put('atualizar-perfil/{id}', [\App\Http\Controllers\UserController::class, 'atualizar_perfil']);
    Route::get('/arquivo-pdf-perfil/{id}',[App\Http\Controllers\UserController::class,'export_perfil_pdf']);

});

//Grupo de routas de Projectos
Route::group(['prefix' => 'blocos', 'middleware' => 'auth'], function () {
    
    Route::resource('/bloco', App\Http\Controllers\BlocoController ::class);
    Route::get('apartamento/{id}',[App\Http\Controllers\ApartamentoController::class,'bloco_apartamento']);
    Route::post('/meus-projectos', [\App\Http\Controllers\BlocoController::class, 'meus_projectos']);
    Route::get('/index-projectos', [\App\Http\Controllers\BlocoController::class, 'index_projectos']);
    Route::get('/estatistica-grafico-projecto', [\App\Http\Controllers\BlocoController::class, 'estatistica_grafico_projectos']);
    Route::get('/estatistica-grafico-dashboard', [\App\Http\Controllers\BlocoController::class, 'estatistica_grafico_dashboard']);
    Route::get('/filtrar-grafico-projecto', [\App\Http\Controllers\BlocoController::class, 'filtrar_grafico_projectos']);
    Route::get('/filtrar-responsavel-projecto', [\App\Http\Controllers\BlocoController::class, 'filtrar_responsavel_projecto']);
    Route::get('/filtrar-estado', [App\Http\Controllers\BlocoController::class, 'filtrar_estado']);
    Route::post('/adicionar-responsavel-projecto', [App\Http\Controllers\BlocoController::class, 'adicionar_responsavel_projecto']);
    Route::post('/projecto-atualizar', [App\Http\Controllers\BlocoController::class, 'projecto_atualizar']);
    Route::get('/filtrar-projecto-percentagem', [App\Http\Controllers\BlocoController::class, 'filtrar_projecto_percentagem']);
    Route::get('/arquivo-pdf-projecto/{id_projeto}',[App\Http\Controllers\BlocoController::class,'export_projecto_pdf']);
    Route::get('/listar-pdf-projectos',[App\Http\Controllers\BlocoController::class,'listar_pdf_projectos']);
 
    Route::put('activar-producao/{id}', [App\Http\Controllers\BlocoController::class, 'activar_producao']);
    Route::put('desactivar-producao/{id}', [App\Http\Controllers\BlocoController::class, 'desactivar_producao']);
    /*Tipo de projecto e a area */
    Route::get('/filtrar-tipo-projectos', [App\Http\Controllers\BlocoController::class, 'filtrar_tipo_projectos']);
    Route::get('/filtrar-tecnologia', [App\Http\Controllers\BlocoController::class, 'filtrar_tecnologias']);

});



    //Grupo de routas de tarefas
Route::group(['prefix' => 'apartamentos', 'middleware' => 'auth'], function () {
    Route::resource('/apartamento', App\Http\Controllers\ApartamentoController::class);
    // atualizar percentagem
    Route::put('rejeitar-tarefa/{id}', [App\Http\Controllers\ApartamentoController::class, 'rejeitar_tarefa']);
    Route::put('tarefa-concluido/{id}', [App\Http\Controllers\ApartamentoController::class, 'tarefa_concluido']);
    Route::put('aceitar-tarefa/{id}', [App\Http\Controllers\ApartamentoController::class, 'aceitar_tarefa']);
    Route::put('cancelamento-tarefa/{id}',[App\Http\Controllers\ApartamentoController::class,'cancelamento_tarefa']);

    // Route::post('/adicionar-responsavel-tarefa', [App\Http\Controllers\ApartamentoController::class, 'adicionar_responsavel_tarefa'] );

});

//Grupo de routas de dvs
Route::group(['prefix' => 'responsaveis', 'middleware' => 'auth'], function () {
    Route::resource('/responsavel', App\Http\Controllers\PessoaController::class);
    Route::get('/estatistica', [\App\Http\Controllers\PessoaController::class, 'estatistica_responsaveis'])->name('estatisticaresponsaveis');
    Route::get('/membros-equipa-pdf', [\App\Http\Controllers\PessoaController::class, 'membros_equipa_pdf'])->name('estatisticaresponsaveis');
    Route::post('/ativar-chefe-area', [App\Http\Controllers\PessoaController::class, 'ativar_chefe_area']);
    Route::post('/desativar-chefe-area', [App\Http\Controllers\PessoaController::class, 'desativar_chefe_area']);
    Route::get('/perfil', function () {
        return Inertia::render('User/Habilidades');
    });

});

Route::group(['prefix' => 'permission', 'middleware' => ['auth']], function () {
    Route::resource('/funcoes-permissoes', \App\Http\Controllers\FuncoesPermissoesController::class);
    Route::put('/concederFuncoes/{user_id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'concederFuncoes']);
    Route::put('/concederPermissoes/{user_id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'concederPermissoes']);
    Route::get('/buscar-roles', [App\Http\Controllers\FuncoesPermissoesController::class, 'getRoles']);
    Route::post('/adicionar-funcao', [App\Http\Controllers\FuncoesPermissoesController::class, 'addRoles']);
    Route::post('/adicionar-permissao', [App\Http\Controllers\FuncoesPermissoesController::class, 'addPermissions']);
    Route::put('/editar-funcao/{id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'editRoles']);
    Route::put('/editar-permissao/{id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'editPermissions']);
    Route::delete('/delete-funcao/{id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'deleteRoles']);
    Route::delete('/delete-permissao/{id}', [App\Http\Controllers\FuncoesPermissoesController::class, 'deletePermissions']);
    Route::get('/associar-funcoes-permissoes', [App\Http\Controllers\FuncoesPermissoesController::class, 'funcao_permissao']);
});


