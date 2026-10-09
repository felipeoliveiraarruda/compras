<?php

namespace App\Models\Passagem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditavel;

class Pessoal extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasFactory, Notifiable, SoftDeletes, Auditavel;

    protected $table = 'pessoais';

    protected $fillable = [
        'codigoUsuario',
        'primeiroNomePessoa',
        'numero_usp',
        'cpfPessoa',
        'rgPessoa',
        'dataNascimentoPessoa',
        'sexoPessoa',
        'celularPessoa',
        'passaporteNumeroPessoa',
        'passaporteValidadePessoa',
    ];

    protected $casts = [
        'dataNascimentoPessoa'      => 'date',
        'passaporteValidadePessoa'  => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'codigoUsuario');
    }    
}
