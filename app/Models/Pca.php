<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Pca extends Model
{
    use \Spatie\Permission\Traits\HasRoles;
    use HasFactory, Notifiable;

    protected $primaryKey = 'codigoPca';
    protected $table      = 'pca';
    protected $uasgs      = array(102169, 102180);

    protected $fillable = [
        'numeroContratacao',
        'statusContratacao',
        'situacaoExecucao',
        'tituloContratacao',
        'categoriaContratacao',
        'codigoUASG',
        'dataEstimadaInicioContratacao',
        'dataEstimadaConclusaoContratacao',
        'prazoEstimadoContratacao',
        'areaRequisitante',
        'numeroDfd',
        'prioridadeDfd',
        'itemDfd',
        'dataConclusaoDfd',
        'classificacaoContratacao',
        'codigoClasse',
        'nomeClasse',
        'codigoPdm',
        'nomePdm',
        'codigoMaterial',
        'descricaoMaterial',
        'unidadeFornecimento',
        'valorUnitario',
        'quantidade',
        'valorTotal',
        'processoSei',
        'situacaoContratacao',
        'codigoPessoaAlteracao'
    ];

    protected $casts = [
        'dataEstimadaInicioContratacao'     => 'date',
        'dataEstimadaConclusaoContratacao'  => 'date',
        'dataConclusaoDfd'                  => 'date',
    ];

    public function getUasgs()
    {
        return $this->uasgs;
    }

    public function demandantes(): HasMany
    {
        return $this->hasMany(Demandante::class, 'codigoPca', 'codigoPca');
    }
}
