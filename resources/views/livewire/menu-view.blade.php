@php use App\Models\Wordpress\Post; @endphp
<div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        <!-- Hero -->
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Explore Our Menu</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Curated categories crafted to suit every taste.</p>
        </div>

        <!-- Search -->
        <div class="max-w-md mx-auto px-1 sm:px-0 relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search menu items..."
                class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring focus:ring-primary-500 dark:placeholder-gray-400 pr-10"
            />
            <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 flex items-center">
                <svg class="w-4 h-4 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
        </div>

        <!-- Parent Categories -->
        @if (!$selectedCategory)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-2">
                @foreach ($parentCategories as $category)
                    <div
                        wire:click="selectCategory('{{ $category['id'] }}')"
                        class="cursor-pointer p-6 rounded-2xl bg-white dark:bg-gray-800 shadow hover:shadow-md border dark:border-gray-700 transition-transform hover:scale-[1.02]"
                    >
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                {{ $category['name'] }}
                            </h3>
                            @if ($category['has_children'])
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">View dishes under this category</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($selectedCategory && !empty($breadcrumbTrail))
            <div class="flex items-center gap-2 flex-wrap text-sm text-gray-600 dark:text-gray-300 mb-4">
                @foreach ($breadcrumbTrail as $index => $crumb)
                    @if ($index > 0)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                        <div class="relative inline-flex items-center">
                            <button
                                wire:click="selectCategory('{{ $crumb['id'] }}')"
                                wire:loading.attr="disabled"
                                wire:target="selectCategory('{{ $crumb['id'] }}')"
                                class="hover:underline disabled:opacity-50"
                            >
                                {{ $crumb['name'] }}
                            </button>

                            <svg
                                wire:loading
                                wire:target="selectCategory('{{ $crumb['id'] }}')"
                                class="w-4 h-4 ml-1 text-gray-400 animate-spin absolute -right-5"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                </path>
                            </svg>
                        </div>
                @endforeach
            </div>
        @endif


        <!-- Subcategories -->
        @if ($items->isNotEmpty() && !empty($childCategories))
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Subcategories</h2>
                    <button
                        wire:click="$set('selectedCategory', null)"
                        class="text-sm text-gray-500 hover:underline dark:text-gray-400"
                    >
                        ← Back to categories
                    </button>
                </div>

                {{-- Pill-style subcategory selector --}}
                <div class="flex overflow-x-auto space-x-3 no-scrollbar pb-1 -mx-1 px-1">
                    @foreach ($childCategories as $subcategory)
                        <button
                            wire:click="selectCategory('{{ $subcategory['id'] }}')"
                            class="shrink-0 px-4 py-2 rounded-full border text-sm font-medium transition
                           border-gray-300 bg-white text-gray-700
                           dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200
                           hover:bg-primary-100 hover:text-primary-800
                           dark:hover:bg-primary-900 dark:hover:text-white"
                        >
                            {{ $subcategory['name'] }}
                            @if ($subcategory['has_children'])
                                <svg class="w-4 h-4 inline-block ml-1 text-gray-400 dark:text-gray-500" fill="none"
                                     stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>

            </div>
        @endif


        <!-- Menu Items -->
        @if ($items->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $item)
                    <div
                        class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 duration-300 overflow-hidden flex">

                        {{-- Image Section --}}
                        <div class="w-32 md:w-48 h-32 md:h-40 bg-gray-100 dark:bg-gray-700 shrink-0">

                            @if ($item->thumbnail_url)
                                <img
                                    src="{{ $item->thumbnail_url }}"
                                    alt="{{ $item->post_title }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover rounded-l-2xl"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No
                                    image
                                </div>
                            @endif

                        </div>

                        {{-- Content Section --}}
                        <div class="flex flex-col justify-between p-4 flex-1 space-y-2">
                            <div class="space-y-1">
                                {{-- Title with highlight --}}
                                <h2 class="text-base md:text-lg font-bold text-gray-900 dark:text-white">
                                    @php
                                        $highlighted = $search
                                            ? preg_replace("/($search)/i", '<mark class=\"bg-yellow-200 dark:bg-yellow-600\">$1</mark>', e($item->post_title))
                                            : e($item->post_title);
                                    @endphp
                                    {!! $highlighted !!}
                                </h2>

                                {{-- Price --}}
                                @php $priceMeta = $item->metadata->firstWhere('meta_key', 'price'); @endphp
                                @if ($priceMeta)
                                    <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        ₦{{ number_format((int) $priceMeta->meta_value, 2) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach ($item->terms as $term)
                                    <span
                                        class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-full">
                                        {{ $term->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Skeleton Loader -->
        <div wire:loading.grid wire:target="selectCategory"
             class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-pulse">
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
