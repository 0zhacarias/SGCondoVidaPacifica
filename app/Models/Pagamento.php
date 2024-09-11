<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamento extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="pagamentos";
    protected $guarded=['id'];
    public function imagens(){
        return $this->hasMany(ImagemPagamento::class);
        
    }
}
