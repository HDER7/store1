<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedColors = [];
    public $selectedSizes = [];
    public $priceRange = [0, 1000];
    public $selectedCategories = [];
    public $sortBy = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedColors' => ['except' => []],
        'selectedSizes' => ['except' => []],
        'priceRange' => ['except' => [0, 1000]],
        'selectedCategories' => ['except' => []],
        'sortBy' => ['except' => 'newest']
    ];

    public function mount()
    {
        // Obtener el precio máximo de los productos para el rango
        $maxPrice = Product::max('price');
        $this->priceRange[1] = $maxPrice ?? 1000;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getAvailableColorsProperty()
    {
        return ProductVariant::distinct('color')->pluck('color');
    }

    public function getAvailableSizesProperty()
    {
        return ProductVariant::distinct('size')->pluck('size');
    }

    public function resetFilters()
    {
        $this->reset(['selectedColors', 'selectedSizes', 'selectedCategories']);
        $this->priceRange = [0, Product::max('price') ?? 1000];
    }

    public function render()
    {
        $query = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategories, function ($query) {
                $query->whereIn('category_id', $this->selectedCategories);
            })
            ->when($this->selectedColors || $this->selectedSizes, function ($query) {
                $query->whereHas('variants', function ($query) {
                    if ($this->selectedColors) {
                        $query->whereIn('color', $this->selectedColors);
                    }
                    if ($this->selectedSizes) {
                        $query->whereIn('size', $this->selectedSizes);
                    }
                });
            })
            ->whereBetween('price', $this->priceRange);

        // Aplicar ordenamiento
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        return view('livewire.product-filter', [
            'products' => $query->paginate(12),
            'categories' => \App\Models\Category::all()
        ]);
    }
}
