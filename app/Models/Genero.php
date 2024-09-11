<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Responsavel;

class Genero extends Model
{
    public function responsavel()
    {
        return $this->hasMany(Responsavel::class);
    }

    use HasFactory;
}
