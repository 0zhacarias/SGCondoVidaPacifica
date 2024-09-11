<?php

namespace App\Exports;

use App\Models\Projeto;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;



class ExportarProjeto implements FromView
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Projeto::all();
    }
    public $projetos;
    public function __construct($projetos)
    {
         $this->projetos=$projetos;
    }
    public function view(): View
    {
        return view('export.arquivo_pdf_projecto', ['arquivo_pdf_projecto' => $this->projetos]);}
}
