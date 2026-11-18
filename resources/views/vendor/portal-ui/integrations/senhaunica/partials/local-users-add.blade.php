<button
    type="button"
    data-portal-modal-open="senhaunica-local-user-create"
    class="flex items-center gap-2 px-4 py-2 bg-white border-2 border-portal text-portal text-sm font-bold rounded-xl hover:bg-portal hover:text-white transition-all shadow-sm"
>
    <x-portal::icon name="fa-plus" />
    Adicionar Usuário Local
</button>

<x-portal::modal
    id="senhaunica-local-user-create"
    title="Adicionar Usuário Local"
    icon="fa-user-plus"
    max-width="lg"
    class="is-hidden"
>
    <form method="POST" action="{{ route(config('senhaunica.localUserRoutes').'.store') }}" class="space-y-4">
        @csrf

        <x-portal::input label="Nome" name="name" :value="old('name')" required />
        <x-portal::input label="E-mail" name="email" type="email" :value="old('email')" required />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-portal::input label="Senha" name="password" type="password" required />
            <x-portal::input label="Confirmação" name="password_confirmation" type="password" required />
        </div>

        <p class="text-xs text-gray-500 italic">Todos os campos são obrigatórios.</p>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <x-portal::button type="button" variant="secondary" data-portal-modal-close>
                Cancelar
            </x-portal::button>
            <x-portal::button type="submit" icon="fa-save">
                Salvar
            </x-portal::button>
        </div>
    </form>
</x-portal::modal>
