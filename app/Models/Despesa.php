<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Despesa extends Model
{
    protected $table="despesas";
    protected $guarded=['id'];
    use HasFactory;
    public function pessoa() {
        return $this->belongsTo(Pessoa::class,'created_by');
        
    }
    function despesas_item():HasMany{
        return $this->hasMany(DespesasItens::class);
        
    }
    public function estado_fatura() {
        return $this->belongsTo(EstadoFactura::class,'estado_factura_id');
        
    }
    public function apartamento() {
        return $this->belongsTo(Apartamento::class,'apartamento_id');
        
    }
}
