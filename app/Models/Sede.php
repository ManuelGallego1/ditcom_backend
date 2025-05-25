<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'coordinador_id',
        'activo'
    ];

    public function coordinador()
    {
        return $this->belongsTo(User::class, 'coordinador_id');
    }
}
