<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id','nif', 'endereco', 'tipo_pagamento', 'ref_pagamento'];

    public function encomendas()
    {
        return $this->hasMany(Encomenda::class);
    }

    public function estampas()
    {
        return $this->hasMany(Estampa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id')->withTrashed();
    }
}
