<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Importacao extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'codigoImportacao';
    protected $table      = 'importacoes';
    protected $dates      = ['created_at'];
        
    protected $fillable = [
        'arquivoImportacao',
        'descricaoImportacao',
        'codigoPessoaAlteracao',
    ];  

    protected $casts = [
        'created_at' => 'date',
    ];
}
