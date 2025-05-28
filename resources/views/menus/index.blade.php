@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-6">Menu</h1>

        @foreach ($menus as $menu)
            <div class="mb-6 p-4 border rounded-lg bg-white shadow">
                <h2 class="text-xl font-semibold">{{ $menu->post_title }}</h2>

                <p class="text-gray-600 text-sm mt-1">
                    @foreach ($menu->terms as $term)
                        <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-semibold text-gray-700">
                        {{ $term->name }}
                    </span>
                    @endforeach
                </p>

                @php
                    $priceMeta = $menu->metadata->firstWhere('meta_key', '_mp_menu_item_price');
                @endphp

                @if ($priceMeta)
                    <p class="text-green-600 font-bold mt-2">₦{{ number_format($priceMeta->meta_value, 2) }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
