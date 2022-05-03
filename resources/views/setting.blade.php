<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting') }}
        </h2>
    </x-slot>

    @php
    $class = 'bg-blue-800';
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    CONNECTION
                    @if (in_array('google', $isConnected))
                        <div class="flex items-center justify-start mt-4">
                            <x-button :class="$class">
                                {{ __('Connected with Google') }}
                            </x-button>
                        </div>
                    @else
                        <form method="GET" action="https://accounts.google.com/o/oauth2/v2/auth">
                            @foreach ($params['google'] as $type => $param)
                            <input type="hidden" name="{{ $type }}" value="{{ $param }}">
                            @endforeach

                            <div class="flex items-center justify-start mt-4">
                                <x-button :class="$class">
                                    {{ __('Connect with Google') }}
                                </x-button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
