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

    protected $table      = 'pcas';
    protected $uasgs      = array(102169, 102180);

    protected $fillable = 
    [
        'numeroArtefato',
        'anoArtefato',
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
        'quantidadeTotal',
        'valorTotal',
        'processoSei',
        'situacaoContratacao',
        'codigoPessoaAlteracao',
    ];

    protected $casts = 
    [
        'numeroArtefato' => 'integer',
        'anoArtefato' => 'integer',
        'codigoUASG' => 'integer',
        'dataEstimadaInicioContratacao' => 'date',
        'dataEstimadaConclusaoContratacao' => 'date',
        'prazoEstimadoContratacao' => 'integer',
        'itemDfd' => 'integer',
        'dataConclusaoDfd' => 'date',
        'codigoClasse' => 'integer',
        'codigoPdm' => 'integer',
        'codigoMaterial' => 'integer',
        'valorUnitario' => 'decimal:2',
        'quantidadeTotal' => 'integer',
        'valorTotal' => 'decimal:2',
        'codigoPessoaAlteracao' => 'integer',
    ];

    public function getUasgs()
    {
        return $this->uasgs;
    }

    public function demandantes(): HasMany
    {
        return $this->hasMany(Demandante::class, 'codigoPca');
    }
}
