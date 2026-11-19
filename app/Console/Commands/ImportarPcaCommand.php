<?php

namespace App\Console\Commands;

use App\Models\PcaItem;
use App\Services\PcaService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportarPcaCommand extends Command
{
    protected $signature = 'pca:importar {ano=2026} {--cnpj=} {--uasg=}';
    protected $description = 'Importa todos os itens paginados do PCA via API Compras.gov.br';

    public function handle(PcaService $pcaService): int
    {
        $ano = $this->argument('ano');
        $filtros = ['anoPcaProjetoCompra' => $ano];

        if ($this->option('cnpj')) {
            $filtros['orgao'] = preg_replace('/\D/', '', $this->option('cnpj'));
        }

        if ($this->option('uasg')) {
            $filtros['codigoUasg'] = $this->option('uasg');
        }

        $pagina = 1;
        $totalImportados = 0;

        $this->info("Iniciando importação do PCA {$ano}...");

        do {
            $this->line("Buscando página {$pagina}...");
            $resposta = $pcaService->consultarPaginaPca($filtros, $pagina, 100);

            if (!$resposta || empty($resposta['resultado'])) {
                if ($pagina === 1) {
                    $this->warn('Nenhum registro encontrado para os filtros informados.');
                }
                break;
            }
            

            foreach ($resposta['resultado'] as $item) 
            {
                // Ajuste os nomes das chaves abaixo conforme o JSON exato retornado no Swagger
                $uasg = $item['codigoUasg'] ?? 'SEM_UASG';
                $numItem = $item['numeroItem'] ?? uniqid();
                $chaveUnica = "{$ano}_{$uasg}_{$numItem}";

                PcaItem::updateOrCreate(
                    ['identificador_unico' => $chaveUnica],
                    [
                        'ano_pca'         => $ano,
                        'cnpj_orgao'      => $item['orgao'] ?? ($filtros['cnpjOrgao'] ?? ''),
                        'codigo_uasg'     => $uasg,
                        'nome_unidade'    => $item['nomeUasg'] ?? null,
                        'tipo_item'       => $item['tipoItem'] ?? null,
                        'codigo_catalogo' => $item['codigoItemCatalogo'] ?? null,
                        'descricao'       => $item['descricaoItemCatalogo'] ?? 'Sem descrição',
                        'quantidade'      => $item['quantidadeItem'] ?? 0,
                        'valor_unitario'  => $item['valorUnitarioItem'] ?? 0,
                        'valor_total'     => $item['valorTotalItem'] ?? 0,
                        'resultado'       => json_encode($item),  
                        'data_desejada'   => $this->parseData($item['dataPrevistaFormalizacaoDemanda'] ?? null),
                    ]
                );

                $totalImportados++;
            }

            // A API de Dados Abertos retorna 'paginasRestantes' no JSON de resposta
            $paginasRestantes = $resposta['paginasRestantes'] ?? 0;
            $pagina++;

            // Pequena pausa para respeitar o Rate Limit da API pública
            usleep(250000); // 250ms

        } while ($paginasRestantes > 0);

        $this->info("Concluído! Total de itens processados: {$totalImportados}");
        return self::SUCCESS;
    }

    private function parseData(?string $data): ?string
    {
        if (!$data) {
            return null;
        }

        try {
            return Carbon::parse($data)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}