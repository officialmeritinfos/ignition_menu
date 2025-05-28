<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Wordpress\MenuItem;
use App\Models\Wordpress\Term;

class MenuView extends Component
{

    public string $selectedCategory = 'all';
    public array $categories = [];
    public $items;

    public string $search = '';

    /**
     * Called when the component is mounted.
     */
    public function mount()
    {
        $this->loadCategories();
        $this->fetchItems();
    }

    public function updatedSearch()
    {
        $this->fetchItems();
    }

    public function selectCategory(string $categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->fetchItems();
    }

    /**
     * Fetch menu items based on the selected category and search term.
     */
    public function fetchItems()
    {
        $query = MenuItem::with(['metadata', 'terms'])
            ->where('post_type', 'mp_menu_item')
            ->where('post_status', 'publish');

        if ($this->selectedCategory !== 'all') {
            $query->whereHas('terms', function ($q) {
                $q->where('term_id', function ($sub) {
                    $sub->select('term_id')
                        ->from('term_taxonomy')
                        ->where('term_taxonomy_id', $this->selectedCategory)
                        ->where('taxonomy', 'mp_menu_category');
                });
            });
        }

        if (!empty($this->search)) {
            $query->where('post_title', 'like', '%' . $this->search . '%');
        }

        $this->items = $query->orderBy('menu_order')->get();
    }

    /**
     * Loads categories with count > 0.
     */
    protected function loadCategories()
    {
        $this->categories = Term::query()
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'mp_menu_category')
            ->where('term_taxonomy.count', '>', 0)
            ->select('term_taxonomy.term_taxonomy_id', 'terms.name')
            ->orderBy('terms.name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->term_taxonomy_id,
                    'name' => $item->name,
                ];
            })
            ->toArray();
    }

    #[Title('Menu')]
    public function render()
    {
        return view('livewire.menu-view');
    }

    public function placeholder(): string
    {
        return <<<'HTML'
    <div class="max-w-4xl mx-auto px-4 py-10 space-y-6 animate-pulse">
        <!-- Placeholder for category scroll -->
        <div class="flex overflow-x-auto gap-2 no-scrollbar mb-6">
            <div class="w-20 h-8 rounded-full bg-gray-200"></div>
            <div class="w-24 h-8 rounded-full bg-gray-200"></div>
            <div class="w-16 h-8 rounded-full bg-gray-200"></div>
            <div class="w-20 h-8 rounded-full bg-gray-200"></div>
        </div>

        <!-- Placeholder for menu cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-4 bg-white rounded-xl shadow space-y-3">
                <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/2 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/3 bg-gray-200 rounded"></div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow space-y-3">
                <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/2 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/3 bg-gray-200 rounded"></div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow space-y-3">
                <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/2 bg-gray-200 rounded"></div>
                <div class="h-4 w-1/3 bg-gray-200 rounded"></div>
            </div>
        </div>
    </div>
    HTML;
    }

}
