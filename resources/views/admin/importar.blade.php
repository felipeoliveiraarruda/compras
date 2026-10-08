<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\CsvImporterService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

new class extends Component
{
    use WithFileUploads;

    public $csv_file;
    public $table_name = 'pca';
    public $coluna     = '';

    protected function rules(): array
    {
        return 
        [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // máximo 10MB
        ];
    }

    protected function messages(): array
    {
        return 
        [
            'csv_file.required' => 'Por favor, selecione um arquivo CSV.',
            'csv_file.mimes' => 'O arquivo deve ser do formato .csv ou .txt.',
            'csv_file.max' => 'O arquivo não pode ser maior que 10MB.',
        ];
    }

    public function import()
    {
        $this->validate();

        try 
        {
            $originalName = $this->csv_file->getClientOriginalName();
            
            // Define o nome da tabela com fallback para o nome do arquivo sanitizado
            $tableName = $this->table_name ? Str::snake($this->table_name) : Str::snake(pathinfo($originalName, PATHINFO_FILENAME));    

            $colunas = Schema::getColumnListing($tableName);

            if (!file_exists($this->csv_file->getRealPath()) || !is_readable($this->csv_file->getRealPath())) 
            {
                throw new \InvalidArgumentException("Arquivo CSV não encontrado ou sem permissão de leitura.");
            }

            $handle = fopen($this->csv_file->getRealPath(), 'r');
            
            if ($handle === false) 
            {
                throw new \RuntimeException("Erro ao abrir o arquivo CSV.");
            }                                

            // 1. Lê a primeira linha para obter o cabeçalho
            $rawHeaders = fgetcsv($handle, 0, ';');        
            
            if (!$rawHeaders) 
            {
                fclose($handle);
                throw new \RuntimeException("O arquivo CSV está vazio ou é inválido.");
            }

            // 2. Processa os registros em lotes (batch insert)
            $batchSize = 500;
            $batchData = [];

            while (($row = fgetcsv($handle, 0, ';')) !== false) 
            {                
                $temp = explode('/', $row[0]);
                $data['numeroArtefato'] = $temp[0];
                $data['anoArtefato']    = $temp[1];

                $i = 0;

                foreach($colunas as $indice => $coluna)
                {                    
                    if ($indice < 3 || $indice > 27) 
                    {
                        continue; 
                    }

                    if ($i == 6 || $i == 7 || $i == 13) 
                    {
                        $data[$coluna] = DateTime::createFromFormat('d/m/Y', trim($row[$i]))->format('Y-m-d');                        
                    } 
                    else if ($i == 22 || $i == 23 || $i == 24)
                    {
                        $valor = str_replace('.', '', trim($row[$i]));
                        $valor = str_replace(',', '.', $valor);
                        $data[$coluna] = number_format($valor, 2, '.', '');
                    }
                    else 
                    {
                        $data[$coluna] = utf8_encode(trim($row[$i]));
                    }

                    $i++;
                }

                DB::table($tableName)->insert($data);
            } 
        
            // Executa a importação
            //$importer->importAndCreateTable($this->csv_file->getRealPath(), $tableName);

            session()->flash('success', "Tabela '{$tableName}': dados importados com sucesso!");

            // Limpa os campos do formulário
            $this->reset(['csv_file', 'table_name']);

        } 
        catch (\Exception $e) 
        {
            session()->flash('error', 'Erro durante a importação: ' . $e->getMessage());            
        }
    }

    public function render()
    {
        

        //dd($coluna);

        return view('admin.importar');
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('admin') ? route('admin') : '#' }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Importação</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header title="Importação" subtitle="Importar dados do PCA por CSV" />
    
    <form wire:submit.prevent="import">        
        <x-portal::input
            label="Arquivo CSV"
            type="file" 
            wire:model="csv_file" 
            accept=".csv,.txt"
            required
        />

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:col-span-2">
            <x-portal::button :href="route('admin.importar')" variant="secondary">
                Cancelar
            </x-portal::button>

            <x-portal::button type="submit" icon="fa-check">
                <span wire:loading.remove wire:target="import">Processar</span>
                <span wire:loading wire:target="import">Importando dados...</span>
            </x-portal::button>
        </div>
    </form>
</div>