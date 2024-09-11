<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Funcoes;
use App\Models\Tarefa;
use App\Models\Projeto;

class Dashboard extends Model
{
    use HasFactory;
    public function funcao()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->hasMany(Funcoes::class);
    }
    public function responsavel()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->hasMany(Responsavel::class);
    }
}
