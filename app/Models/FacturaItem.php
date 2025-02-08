<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    use HasFactory;
    
    protected $table='factura_itens';
    protected $guarded=['id'];
    
    public function factura() {
        return $this->belongsTo(Factura::class);
        
    }
    function pessoa()  {
        return $this->belongsTo(Pessoa::class);
        
    }
    function servico() {
        return $this->belongsTo(Servico::class);
        
    }
}
