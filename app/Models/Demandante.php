<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditavel;
use Uspdev\Replicado\Pessoa;

class Demandante extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasFactory, Notifiable, SoftDeletes, Auditavel;

    protected $primaryKey = 'codigoDemandante';
       
    protected $fillable = [
        'codigoPca',
        'codigoPessoa',
        'codigoPessoaCriacao',
        'codigoPessoaAlteracao',
    ];

    public function pca(): BelongsTo
    {
        return $this->belongsTo(Pca::class, 'codigoPca', 'codigoPca');
    }
}
