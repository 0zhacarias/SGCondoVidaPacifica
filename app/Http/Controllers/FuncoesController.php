<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Funcoes;
use App\Models\Responsavel;

class FuncoesController extends Controller
{

    public function index()
    {
        $data['funcoes']=Funcoes::all();
        return Inertia::render('User/Funcoes',$data);
    }

    public function store(Request $request)
    {
        //dd($request);
        try
        {
            Funcoes::create($request ->all());
       //     return redirect('/tabela_de_apoio/funcoes');
            return redirect()->back()->with('success', 'foi possivel');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'foi possivel');

        }

    }
public function create(){

}
public function edit(Request $request){

}
public function show()
{
    # code...
}

     public function update(Request $request,$id)
    {
        try
        {
            $funcao=Funcoes::find($id);
            $funcao->update($request->all());

            return redirect()->back()->with('success', 'foi possivel');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'foi possivel');

        }

        return redirect('tabela_de_apoio/funcoes');
    }
    public function destroy($id){
        $funcao=Funcoes::find($id);
        $funcao->delete();
    }
}


