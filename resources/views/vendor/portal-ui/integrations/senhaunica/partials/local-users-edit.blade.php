<x-portal::modal
    id="senhaunica-local-user-edit"
    title="Alterar Usuário Local"
    icon="fa-user-pen"
    max-width="lg"
    class="is-hidden"
    data-portal-local-user-modal
>
    <form method="POST" action="" class="space-y-4" data-portal-local-user-form>
        @csrf
        @method('PUT')

        <x-portal::input label="Nome" name="name" data-portal-local-user-name required />
        <x-portal::input label="E-mail" name="email" type="email" data-portal-local-user-email required />

        <div class="bg-gray-50 p-4 rounded-xl space-y-4 border border-gray-100 mt-2">
            <label class="flex items-center gap-3 text-sm font-bold text-gray-700">
                <input
                    type="checkbox"
                    name="senha"
                    value="1"
                    data-portal-local-user-password-toggle
                    class="form-checkbox w-5 h-5 text-portal border-gray-300 rounded focus:ring-portal"
                >
                Alterar senha
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-portal::input label="Nova senha" name="password" type="password" readonly data-portal-local-user-password />
                <x-portal::input label="Confirmação" name="password_confirmation" type="password" readonly data-portal-local-user-password />
            </div>
        </div>

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
