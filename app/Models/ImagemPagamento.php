<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagemPagamento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='imagem_pagamentos';
    protected $guarded=['id'];
}
