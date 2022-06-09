<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Midias') }}
        </h2>
        <a href="{{ route('dashboard.medias.create') }}">Add media</a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap">
                        <tr>
                            <td>Conta</td>
                            <td>Media</td>
                            <td>Publicado em</td>
                        </tr>
                        @foreach ($medias as $media)
                            <tr>
                                <td>{{ $media->account->social_id }}</td>
                                <td>{{ $media->file }}</td>
                                <td>{{ $media->published_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
