<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contas') }}
        </h2>
        <a href="{{ route('dashboard.auth.redirect', ['provider'=> 'youtube']) }}">Add canal youtube</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap">
                        <tr>
                            <td>#</td>
                            <td>Nome</td>
                            <td>Provedor</td>
                            <td>Usuario</td>
                        </tr>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->social_id }}</td>
                                <td>{{ $account->name }}</td>
                                <td>{{ $account->provider }}</td>
                                <td>{{ $account->user->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
