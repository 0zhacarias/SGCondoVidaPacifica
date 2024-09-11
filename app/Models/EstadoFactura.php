<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoFactura extends Model
{
    use HasFactory;
    protected $table="estado_facturas";
    protected $guarded=['id'];
}
