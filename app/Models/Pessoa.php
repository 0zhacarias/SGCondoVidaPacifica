<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlTarefa;
use App\Models\Projeto;
use App\Models\User;
use App\Models\EstadosCivis;
use App\Models\Genero;
use App\Models\Dashboard;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pessoa extends Model
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    protected $table="pessoas";
    protected $guarded=['id'];
   

    
    public function projecto()
    {
        return $this->hasMany(Bloco::class);
   }
// Muitos para Muitos
    public function apartamento()
    {
        return $this->hasOne(Apartamento::class,'condomino_id');
    }
    public function estado_apartamento()
    {
        return $this->belongsTo(EstadoApartamento::class,'estado_apartamento_id');
    }

    public function funcao()
    {
        // return $this->belongsTo(Projeto::class, 'responsavel_id');
        return $this->belongsTo(Funcoes::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
 
    }
    public function genero(){
        return $this->belongsTo(Genero::class);

    }
    public function estadoCivil(){
        return $this->belongsTo(EstadosCivis::class);
        
    }
    public function tipodocumento(){
        return $this->belongsTo(TipoDocumentoIdentificacao::class);
    }
    public function dashboard(){
        return $this->belongsTo(Dashboard::class);
        
    }
    protected static function boot() {
        parent::boot();
        $relacoes=['equipaProjecto','controlTarefa','projecto','user'];
        foreach ($relacoes as $key => $relacao) {
            static::deleting(function ($responsavelDelete) use ($relacao) {
                $responsavelDelete->{$relacao}()->delete();
            });
        }
    }
}
