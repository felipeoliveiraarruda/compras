@component('mail::message')
# Fase Preparatória Iniciada

Olá,

A contratação **{{ $pca->tituloContratacao }}** (UASG: {{ $pca->codigoUASG }} - Nº {{ $pca->numeroContratacao }}) atingiu a data de início agendada e seu status foi alterado para **Em fase preparatória**.

**Atenção para os próximos passos:**
O responsável pela contratação deve acessar o sistema e cadastrar/atualizar o **Número do Processo SEI**.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent