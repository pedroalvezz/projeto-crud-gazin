<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = ['nome', 'descricao', 'preco'];
}
