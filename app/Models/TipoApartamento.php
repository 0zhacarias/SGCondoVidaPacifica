<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoApartamento extends Model
{
    use HasFactory;
    protected $table="tipo_apartamentos";
    protected $guarded=['id'];
}
