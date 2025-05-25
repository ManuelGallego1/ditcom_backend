<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movil extends Model
{
    use HasFactory;

    protected $table = 'moviles';

    protected $fillable = [
        'min',
        'imei',
        'iccid',
        'tipo',
        'plan_id',
        'celulares_id',
        'cliente_cc',
        'factura',
        'ingreso_caja',
        'valor_total',
        'tipo_producto',
        'vendedor_id',
        'sede_id',
        'financiera',
        'coordinador_id',
        'valor_recarga',
        'estado'
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function coordinador()
    {
        return $this->belongsTo(User::class, 'coordinador_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_cc', 'cc');
    }

    public function celular()
    {
        return $this->belongsTo(Celular::class, 'celulares_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
