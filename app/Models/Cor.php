<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cor extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $table = 'cores';
    protected $primaryKey = 'codigo';
    protected $keyType = 'string';

    protected $fillable = ['codigo', 'nome'];

    public function tshirts()
    {
        return $this->hasMany(Tshirt::class, 'cor_codigo', 'codigo');
    }
}
