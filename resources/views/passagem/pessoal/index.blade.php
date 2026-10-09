<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Uspdev\Replicado\Pessoa;
use App\Models\Passagem\Pessoal;

new class extends Component
{
    use WithFileUploads;

    public $codpes;
    public $name;
    public $email;
    public $primeiroNomePessoa;
    public $sobreNomePessoa;
    public $cpfPessoa;
    public $rgPessoa;
    public $dataNascimentoPessoa;
    public $sexoPessoa;
    public $celularPessoa;
    public $passaportePessoa;
    public $passaporteValidadePessoa;
    
    public function mount()
    {
        $pessoa = Pessoal::where('codigoUsuario', auth()->id())->first();        

        if ($pessoa) 
        {
            $this->primeiroNomePessoa = $pessoa->primeiroNomePessoa;
            $this->sobrenomeNomePessoa = $pessoa->sobrenomeNomePessoa;
            $this->cpfPessoa = $pessoa->cpfPessoa;
            $this->rgPessoa = $pessoa->rgPessoa;
            $this->dataNascimentoPessoa = $pessoa->dataNascimentoPessoa?->format('Y-m-d');
            $this->sexoPessoa = $pessoa->sexoPessoa;
            $this->celularPessoa = $pessoa->celularPessoa;
            $this->passaportePessoa = $pessoa->passaportePessoa;
            $this->passaporteValidadePessoa = $pessoa->passaporteValidadePessoa?->format('Y-m-d');
        } 
        else 
        {
            // Pré-preenche com dados básicos do User do Laravel
            $nome = explode(' ', auth()->user()->name);

            $this->codpes               = auth()->user()->codpes;
            $this->name                 = auth()->user()->name;
            $this->email                = auth()->user()->email;
            $this->primeiroNomePessoa   = $nome[0];
            $this->sobreNomePessoa      = end($nome);
            $this->dataNascimentoPessoa = DateTime::createFromFormat('d/m/Y', trim(Pessoa::nascimento(auth()->user()->codpes)))->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'primeiroNomePessoa'        => 'required|string|max:255',
            'sobreNomePessoa'           => 'required|string|max:255',
            'cpfPessoa'                 => 'required|string|max:14',
            'rgPessoa'                  => 'required|string|max:20',
            'dataNascimentoPessoa'      => 'required|date|before:today',
            'sexoPessoa'                => 'required|string|max:20',
            'celularPessoa'             => 'required|string|max:20',
            'passaportePessoa'          => 'nullable|string|max:50',
            'passaporteValidadePessoa'  => 'nullable|date|after:today',
        ];
    }

    // Mapeamento dos nomes dos Labels
    protected function validationAttributes(): array
    {
        return [
            'primeiroNomePessoa'       => 'Primeiro Nome',
            'sobrenomeNomePessoa'      => 'Sobrenome',
            'cpfPessoa'                => 'CPF',
            'rgPessoa'                 => 'RG',
            'dataNascimentoPessoa'     => 'Data de Nascimento',
            'sexoPessoa'               => 'Sexo',
            'celularPessoa'            => 'Celular / WhatsApp',
            'passaportePessoa'         => 'Passaporte',
            'passaporteValidadePessoa' => 'Validade do Passaporte',
        ];
    }

    // Mensagens genéricas que utilizam o label dinâmico
    protected function messages(): array
    {
        return [
            'required' => ':attribute é obrigatório.',
            'before'   => ':attribute precisa ser uma data no passado.',
            'after'    => ':attribute precisa ser uma data futura.',
        ];
    }

    public function save()
    {
        $this->validate();
        session()->flash('success', 'Ficha pessoal atualizada com sucesso!');

        $userId = auth()->id();

        Pessoal::updateOrCreate(
            ['codigoUsuario' => $userId],
            [
                'primeiroNomePessoa'        => $this->primeiroNomePessoa,
                'sobreNomePessoa'           => $this->sobreNomePessoa,
                'cpfPessoa'                 => $this->cpfPessoa,
                'rgPessoa'                  => $this->rgPessoa,
                'dataNascimentoPessoa'      => $this->dataNascimentoPessoa,
                'sexoPessoa'                => $this->sexoPessoa,
                'celularPessoa'             => $this->celularPessoa,
                'passaportePessoa'          => ($this->passaportePessoa == '' ? NULL : $this->passaportePessoa),
                'passaporteValidadePessoa'  => ($this->passaporteValidadePessoa == '' ? NULL : $this->passaporteValidadePessoa),
                'codigoPessoaCriacao'       => $userId,
                'codigoPessoaAlteracao'     => $userId,
            ]
        ); 
        
        session()->flash('success', 'Ficha pessoal atualizada com sucesso!');
    }

    public function render()
    {
        return view('passagem.pessoal.index');
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('passagem.index') ? route('admin.dashboard') : '#' }}" class="hover:text-gray-700 hover:underline">Passagens EEL</a>   
    <span>/</span>
    <span>Ficha Pessoal</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header title="Ficha Pessoal" subtitle="Dados usados automaticamente quando você for solicitante ou passageiro." />

    <x-portal::flash-messages />
  
    <form wire:submit.prevent="save" class="space-y-6" novalidate>
        <x-portal::card>
            <x-slot:header>
                <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-portal-gradient text-white shadow-sm">
                            <i class="fa fa-info-circle text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-gray-900 truncate">Identificação para a MedTour</h2>
                        </div>
                    </div>

                    <!-- Botões alinhados à direita -->
                    <div class="flex items-center gap-2 flex-shrink-0 sm:ms-auto">
                    <x-portal::button type="submit" icon="fa-solid fa-floppy-disk" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Salvar</span>
                            <span wire:loading wire:target="save">Salvando...</span>
                        </x-portal::button>

                        <x-portal::button :href="route('admin.dashboard')" variant="secondary" icon="fa-solid fa-arrow-rotate-left">
                            Cancelar
                        </x-portal::button>
                    </div>
                </div>
            </x-slot:header>            

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-4 md:grid-cols-4">
                <x-portal::input
                    label="Número USP"
                    wire:model.live="codpes"
                    disabled
                />

                <div class="md:col-span-2">
                    <x-portal::input
                        label="Nome Completo"
                        wire:model.live="name"
                        disabled
                    />
                </div>        

                <x-portal::input
                    label="E-mail"
                    wire:model.live="email"
                    disabled
                />                
            </div>                

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-3 md:grid-cols-3">
                <x-portal::input
                    label="CPF"
                    wire:model.live="cpfPessoa"
                    x-mask="999.999.999-99"
                    placeholder="000.000.000-00"
                    maxlength="14"
                    @class(['!border-red-500 focus:!ring-red-500' => $errors->has('primeiroNomePessoa')])
                    required
                />

                <x-portal::input
                    label="Primeiro Nome"
                    wire:model.live="primeiroNomePessoa"
                    required
                />

                <x-portal::input
                    label="Sobrenome"
                    wire:model.live="sobreNomePessoa"
                    required
                />
            </div>

            <div class="grid grid-cols-1 gap-2 lg:grid-cols-4 md:grid-cols-4">

                <x-portal::input
                    label="RG"
                    wire:model.live="rgPessoa"
                    maxlength="20"
                    required
                />

                <x-portal::input
                    label="Data de Nascimento"
                    type="date"
                    wire:model.live="dataNascimentoPessoa"
                    required
                />

                <x-portal::select
                    label="Sexo"
                    wire:model.live="sexoPessoa"
                    :options="['Masculino' => 'Masculino', 'Feminino' => 'Feminino', 'Outro' => 'Outro', 'Prefiro não informar' => 'Prefiro não informar']"
                    :error="$errors->first('sexoPessoa')"
                    required
                />

                <x-portal::input
                    label="Celular"
                    wire:model.live="celularPessoa"
                    x-mask="(99) 99999-9999"
                    placeholder="(00) 00000-0000"
                    required
                />                  
            </div>
        </x-portal::card>

        <x-portal::card>
            <x-slot:header>
                <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-portal-gradient text-white shadow-sm">
                            <i class="fa-solid fa-plane-up text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-gray-900 truncate">Viagem Internacional</h2>
                        </div>
                    </div>
                </div>
            </x-slot:header>

            <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                <x-portal::input
                    label="Passaporte"
                    wire:model.live="passaportePessoa"
                />

                <x-portal::input
                    label="Validade"
                    type="date"
                    wire:model.live="passaporteValidadePessoa"
                />   
            </div>            
        </x-portal::card>            
    </form>
</div>