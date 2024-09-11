<?php

namespace App\Models;
use App\Models\Pessoa;

use App\Models\Pagamento;
use App\Models\Apartamento;
use App\Models\FacturaItem;
use App\Models\EstadoFatura;
use App\Models\EstadoFactura;
use App\Models\ImagemPagamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='facturas';
    protected $guarded=['id'];
    
    public function estado_fatura() {
        return $this->belongsTo(EstadoFactura::class,'estado_factura_id');
        
    }
    public function apartamento() {
        return $this->belongsTo(Apartamento::class,'apartamento_id');
        
    }
    function pagamento()  {
        return $this->hasOne(Pagamento::class);
    }
    public function pessoa() {
        return $this->belongsTo(Pessoa::class,'created_by');
        
    }
    function factura_itens() {
        return $this->hasMany(FacturaItem::class);
    }
    function imagem(){
        return $this->hasMany(ImagemPagamento::class,'factura_id');
    }
protected static function boot(){
    parent::boot();
    $relacionamentos=['factura_itens','pagamento'];
    foreach($relacionamentos as $key=>$relacao){
        static::deleting(function ($model) use ($relacao){
             $model->{$relacao}()->delete();
        });
     }
    }

}
