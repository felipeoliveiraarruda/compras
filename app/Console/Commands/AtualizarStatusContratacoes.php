<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pca;
use App\Mail\ContratacaoFasePreparatoriaMail;
use Illuminate\Support\Facades\Mail;
use Uspdev\Replicado\Pessoa;
use Carbon\Carbon;

class AtualizarStatusContratacoes extends Command
{
    protected $signature = 'pca:atualizar-status';
    protected $description = 'Atualiza contratações para "Em fase preparatória" na data de início e notifica os demandantes.';

    public function handle()
    {
        $hoje = Carbon::today()->format('Y-m-d');

        // Busca contratações onde a data de início é hoje ou anterior e o status ainda não foi alterado
        $pcas = Pca::with('demandantes')
            ->where('statusContratacao', 'Aprovada')
            ->where('situacaoContratacao', '!=', 'Em fase preparatória')
            ->whereDate('dataEstimadaInicioContratacao', '<=', $hoje) // Ajuste para o nome correto da sua coluna de data
            ->get();

        foreach ($pcas as $pca) {
            // 1. Atualiza a situação
            $pca->update([
                'situacaoContratacao' => 'Em fase preparatória',
            ]);

            // 2. Notifica todos os demandantes vinculados
            if ($pca->demandantes && $pca->demandantes->isNotEmpty()) {
                foreach ($pca->demandantes as $demandante) {
                    try {
                        // Busca o e-mail do demandante via Replicado USP
                        $email = Pessoa::email($demandante->codigoPessoa);
                        //$email = 'dev.ci.eel@usp.br';

                        if ($email) 
                        {
                            Mail::to($email)->queue(new ContratacaoFasePreparatoriaMail($pca, $demandante));
                            $this->info("Contratação {$pca->codigoUASG}-{$pca->numeroContratacao} enviou e-mail para {$email} com sucesso.");
                        }
                    } catch (\Throwable $e) {
                        $this->error("Erro ao enviar e-mail para NUSP {$demandante->codigoPessoa}: " . $e->getMessage());
                    }
                }
            }

            $this->info("Contratação {$pca->codigoUASG}-{$pca->numeroContratacao} atualizada com sucesso.");
        }

       /* Mail::to($email)->queue(new ContratacaoFasePreparatoriaMail($pca, $demandante));
        $this->info("Contratação {$pca->codigoUASG}-{$pca->numeroContratacao} enviou e-mail para {$email} com sucesso.");*/

        return Command::SUCCESS;
    }
}