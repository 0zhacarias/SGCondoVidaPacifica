<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class Apartamento extends Model
{
    use HasFactory;
    protected $table="apartamentos";
    protected $guarded=['id']; //para não ter que criar fillble

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('d M Y');
    }

    public function projecto()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->belongsTo(Bloco::class,'projecto_id');
    }
    public function estadoTarefa()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->belongsTo(EstadoTarefa::class);
    }


    public function controlTarefas()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->hasMany(ControlTarefa::class,'tarefa_id');
    }
    public function tipo_apartamento()
    {
        return $this->belongsTo(TipoApartamento::class,'tipo_apartamento_id');
    }
    public function estado_apartamento()
    {
        return $this->belongsTo(EstadoApartamento::class,'estado_apartamento_id');
    }
    public function condomino(){
        return $this->belongsTo(Pessoa::class,'condomino_id');
    }

}
