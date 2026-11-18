<x-portal::modal
    id="senhaunica-permissions-modal"
    title="Permissões do usuário"
    icon="fa-user-shield"
    max-width="4xl"
    class="is-hidden"
    data-portal-permissions-modal
>
    <div class="mb-5 text-sm text-gray-500">
        Editando permissões de <strong data-portal-permissions-user class="text-gray-900">usuário</strong>.
    </div>

    <div data-portal-permissions-loading class="hidden p-8 text-center text-portal">
        <x-portal::icon name="fa-circle-notch fa-spin" class="text-3xl" />
        <span class="block mt-2 font-bold">Carregando...</span>
    </div>

    <div data-portal-permissions-error class="hidden p-4 mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
        Não foi possível carregar as permissões do usuário.
    </div>

    <form method="POST" action="" data-portal-permissions-form>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Permissões da Aplicação</div>
                <div class="space-y-2">
                    @foreach($permissoesAplicacao as $permission)
                        <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg transition-all cursor-pointer border border-transparent hover:border-gray-100">
                            <input
                                type="checkbox"
                                name="permission_app[]"
                                value="{{ $permission->name }}"
                                data-portal-permission-name="{{ $permission->name }}"
                                data-portal-permission-guard="{{ \App\Models\User::$appNs }}"
                                class="form-checkbox w-5 h-5 text-portal border-gray-300 rounded focus:ring-portal"
                            >
                            <span class="text-sm font-medium text-gray-700">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Roles da Aplicação</div>
                <div class="space-y-2">
                    @foreach($rolesAplicacao as $role)
                        <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg transition-all cursor-pointer border border-transparent hover:border-gray-100">
                            <input
                                type="checkbox"
                                name="role_app[]"
                                value="{{ $role->name }}"
                                data-portal-role-name="{{ $role->name }}"
                                data-portal-role-guard="{{ \App\Models\User::$appNs }}"
                                class="form-checkbox w-5 h-5 text-portal border-gray-300 rounded focus:ring-portal"
                            >
                            <span class="text-sm font-medium text-gray-700">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Permissão Hierárquica</div>

                    <div data-portal-permissions-env class="hidden p-2 bg-red-50 border border-red-100 rounded-lg">
                        <span data-portal-permissions-env-label class="text-red-700 text-xs font-bold"></span>
                    </div>

                    <div data-portal-permissions-hierarchy class="space-y-2">
                        @foreach(\App\Models\User::$permissoesHierarquia as $permission)
                            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg transition-all cursor-pointer border border-transparent hover:border-gray-100">
                                <input
                                    type="radio"
                                    name="level"
                                    value="{{ $permission }}"
                                    data-portal-permission-name="{{ $permission }}"
                                    data-portal-permission-guard="{{ \App\Models\User::$hierarquiaNs }}"
                                    class="form-radio w-5 h-5 text-portal border-gray-300 focus:ring-portal"
                                >
                                <span class="text-sm font-medium text-gray-700">{{ $permission }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Vínculos</div>
                    <div data-portal-permissions-links class="text-sm text-gray-600 font-medium bg-white p-3 rounded-lg border border-gray-100">
                        Nenhum
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
            <x-portal::button type="button" variant="secondary" data-portal-modal-close>
                Cancelar
            </x-portal::button>
            <x-portal::button type="submit" icon="fa-save">
                Salvar alterações
            </x-portal::button>
        </div>
    </form>
</x-portal::modal>
