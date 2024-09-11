<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Responsavel;

class EstadosCivis extends Model
{
    protected $table="estados_civis";
    protected $guarded=['id'];
    public function responsavel()
    {
        return $this->hasMany(Responsavel::class);
    }

    use HasFactory;
}
