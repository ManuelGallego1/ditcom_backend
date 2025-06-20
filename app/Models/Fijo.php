<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fijo extends Model
{
    use HasFactory;

    protected $table = 'fijos';

    protected $fillable = [
        'fecha_instalacion',
        'fecha_legalizacion',
        'servicios_adicionales',
        'estrato',
        'cuenta',
        'OT',
        'tipo_producto',
        'total_servicios',
        'total_adicionales',
        'cliente_cc',
        'sede_id',
        'vendedor_id',
        'estado',
        'convergente',
        'ciudad',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_cc', 'cc');
    }
}
