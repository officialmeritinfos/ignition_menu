<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        <!-- Hero Section -->
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Explore Our Menu</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Carefully curated categories — something for every taste.</p>
        </div>

        <!-- Sticky Search + Category Scroll -->
        <div class="sticky top-0 z-20 bg-white dark:bg-gray-900 pt-4 pb-3 space-y-2">

            <!-- Search -->
            <div class="max-w-md mx-auto px-1 sm:px-0 relative mb-5">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search menu items..."
                    class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring focus:ring-primary-500 dark:placeholder-gray-400 pr-10"
                />

                <!-- Loading Spinner -->
                <div
                    wire:loading.flex
                    wire:target="search"
                    class="absolute inset-y-0 right-3 flex items-center"
                >
                    <svg class="w-4 h-4 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        ></path>
                    </svg>
                </div>
            </div>


            <!-- Category Scroll -->
            <div class="flex overflow-x-auto gap-3 no-scrollbar px-1 sm:px-0">
                <button
                    wire:click="selectCategory('all')"
                    class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border transition-all
            {{ $selectedCategory === 'all' ? 'bg-black text-white dark:bg-white dark:text-black' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                    All
                </button>

                @foreach ($categories as $category)
                    <button
                        wire:click="selectCategory('{{ $category['id'] }}')"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap border transition-all
                {{ $selectedCategory == $category['id'] ? 'bg-black text-white dark:bg-white dark:text-black' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>

        </div>




        <!-- Menu Grid -->
        <div wire:loading.remove wire:target="selectCategory">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($items as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg border dark:border-gray-700 transition transform hover:-translate-y-1 duration-300">
                        <div class="rounded-t-2xl overflow-hidden h-48 sm:h-40 bg-gray-100 dark:bg-gray-700">
                            @php $image = $item->metadata->firstWhere('meta_key', 'mp_menu_gallery'); @endphp
                            @if ($image && !empty($image->meta_value))
                                <img src="{{ $image->meta_value }}" alt="{{ $item->post_title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No image</div>
                            @endif
                        </div>
                        <div class="p-5 space-y-2">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white truncate">
                                @php
                                    $highlighted = $search
                                        ? preg_replace("/($search)/i", '<mark class=\"bg-yellow-200 dark:bg-yellow-600\">$1</mark>', e($item->post_title))
                                        : e($item->post_title);
                                @endphp
                                {!! $highlighted !!}

                            </h2>
                            @php $priceMeta = $item->metadata->firstWhere('meta_key', 'price'); @endphp
                            @if ($priceMeta)
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    ₦{{ number_format((int) $priceMeta->meta_value, 2) }}
                                </div>
                            @endif
                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach ($item->terms as $term)
                                    <span class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-full">
                                        {{ $term->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-12">
                        No menu items found in this category.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Skeleton Loader -->
        <div wire:loading.grid wire:target="selectCategory" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-pulse">
            @for ($i = 0; $i < 6; $i++)
                <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl">
                    <div class="h-48 bg-gray-200 dark:bg-gray-700 rounded-t-2xl"></div>
                    <div class="p-5 space-y-4">
                        <div class="h-5 w-2/3 bg-gray-300 dark:bg-gray-600 rounded"></div>
                        <div class="h-4 w-1/4 bg-green-300 dark:bg-green-600 rounded"></div>
                        <div class="flex gap-2">
                            <div class="h-6 w-14 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                            <div class="h-6 w-16 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

    </div>
</div>
