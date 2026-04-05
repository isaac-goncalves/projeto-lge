<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produtividade extends Model
{
    protected $table = 'produtividades';

    protected $fillable = [
        'linha',
        'data_producao',
        'quantidade_produzida',
        'quantidade_defeitos',
    ];

    protected $casts = [
        'data_producao' => 'date',
        'quantidade_produzida' => 'int',
        'quantidade_defeitos' => 'int',
    ];

    public $timestamps = false;
}
