<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Responsavel;
use DateTimeInterface;

class Funcoes extends Model
{
    protected $table="funcoes";
    protected $guarded=['id']; //para não ter que criar fillble


    protected function serializeDate(DateTimeInterface $date){
        return $date->format('d M Y');
    }
    public function responsavel()
    {
         return $this->hasMany(Responsavel::class);
        //esse modelo faz referença a tabela funçoes que é a class forte.
    }
    use HasFactory;
    public function dashboard()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->belongsTo(Dashboard::class);
    }
}
