<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'cc',
        'p_nombre',
        's_nombre',
        'p_apellido',
        's_apellido',
        'email',
        'numero'
    ];
}
