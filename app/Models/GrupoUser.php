<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoUser extends Model
{
    protected $table = 'grupos_users';

    protected $fillable = [
        'user_id',
        'grupo_id',
        'es_admin',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
