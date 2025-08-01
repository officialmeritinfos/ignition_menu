<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Wordpress\MenuItem;
use App\Models\Wordpress\Term;

class MenuView extends Component
{
    public array $parentCategories = [];
    public array $childCategories = [];
    public string|null $selectedCategory = null;

    public string $search = '';
    public Collection $items;

    public function mount(): void
    {
        $this->items = collect();
        $this->loadParentCategories();
    }

    public function updatedSearch(): void
    {
        $this->fetchItems();
    }

    public function loadParentCategories(): void
    {
        $terms = 'terms';
        $taxonomy = 'term_taxonomy';

        $this->parentCategories = Term::query()
            ->from("$terms as t")
            ->join("$taxonomy as tt", 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'mp_menu_category')
            ->where('tt.parent', 0)
            ->selectRaw('term_taxonomy_id as id, name as name')
            ->orderBy('t.name')
            ->get()
            ->map(function ($item) {
                $hasChildren = \DB::connection('wordpress')
                    ->table('term_taxonomy')
                    ->where('parent', $item->id)
                    ->where('taxonomy', 'mp_menu_category')
                    ->exists();

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'has_children' => $hasChildren,
                ];
            })
            ->toArray();
    }


    public function selectCategory(string $termTaxonomyId): void
    {
        $this->selectedCategory = $termTaxonomyId;
        $this->items = collect();
        $this->childCategories = $this->getChildren($termTaxonomyId);

        if (empty($this->childCategories)) {
            $this->fetchItems($termTaxonomyId);
        }
    }

    public function getChildren(string $termTaxonomyId): array
    {
        $terms = 'terms';
        $taxonomy = 'term_taxonomy';

        return Term::query()
            ->from("$terms as t")
            ->join("$taxonomy as tt", 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'mp_menu_category')
            ->where('tt.parent', $termTaxonomyId)
            ->selectRaw('term_taxonomy_id as id, name as name')
            ->orderBy('t.name')
            ->get()
            ->map(function ($item) {
                $hasChildren = \DB::connection('wordpress')
                    ->table('term_taxonomy')
                    ->where('parent', $item->id)
                    ->where('taxonomy', 'mp_menu_category')
                    ->exists();

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'has_children' => $hasChildren,
                ];
            })
            ->toArray();
    }

    public function fetchItems(string $termTaxonomyId = null): void
    {
        $taxonomyId = $termTaxonomyId ?? $this->selectedCategory;

        $query = MenuItem::with(['metadata', 'terms', 'thumbnailMeta'])
            ->where('post_type', 'mp_menu_item')
            ->where('post_status', 'publish');

        if (! is_null($taxonomyId)) {
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

    #[Title('Menu')]
    public function render()
    {
        return view('livewire.menu-view');
    }

    public function placeholder(): string
    {
        return <<<'HTML'
        <div class="max-w-4xl mx-auto px-4 py-10 space-y-6 animate-pulse">
            <div class="flex overflow-x-auto gap-2 no-scrollbar mb-6">
                <div class="w-20 h-8 rounded-full bg-gray-200"></div>
                <div class="w-24 h-8 rounded-full bg-gray-200"></div>
                <div class="w-16 h-8 rounded-full bg-gray-200"></div>
                <div class="w-20 h-8 rounded-full bg-gray-200"></div>
            </div>
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
