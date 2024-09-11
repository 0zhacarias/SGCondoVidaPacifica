<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespesasItens extends Model
{
    protected $table="despesas_itens";
    protected $guarded=['id'];
    use HasFactory;
}
