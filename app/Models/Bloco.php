<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    protected $table="blocos";
    protected $guarded=['id']; //para não ter que criar fillble
    use HasFactory;
    public function tarefas()
    {
         return $this->hasMany(Apartamento::class,'projecto_id');
        //return $this->hasMany('App\Models\Tarefa');
    }
    public function sindico()
    {
        return $this->belongsTo(Pessoa::class,'sindico_id');
    }

  
    protected static function boot() {
        parent::boot();
        $relacoes=['equipaProjecto','tarefas','notificacaoGrupo','projectoArquivoUpload','controlProjectoTecnologia'];
        foreach ($relacoes as $key=> $relacao) {
            static::deleting(function ($ProjectolDelete) use ($relacao) {
           
                    $ProjectolDelete->{$relacao}()->delete();
                
            });
        }
    }
}
