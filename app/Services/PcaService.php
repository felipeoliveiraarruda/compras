<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PcaService
{
    protected string $baseUrl = 'https://dadosabertos.compras.gov.br/modulo-pgc';

    /**
     * Busca uma página específica do PCA na API do Compras.gov.br
     */
    public function consultarPaginaPca(array $filtros, int $pagina = 1, int $tamanhoPagina = 100): ?array
    {
        $queryParams = array_merge($filtros, [
            'pagina' => $pagina,
            'tamanhoPagina' => $tamanhoPagina,
        ]);

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000) // Tenta novamente em caso de instabilidade da API
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/1_consultarPgcDetalhe", $queryParams);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Erro na API Compras.gov.br (Status {$response->status()}): " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("Falha de conexão com Compras.gov.br: " . $e->getMessage());
            return null;
        }
    }
}