<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;


class PropertyListing extends Component
{
    use WithPagination;

    // Filtering and searching functionality
    public $search = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $type = '';
    public $listingType = '';
    public $minBedrooms = '';
    public $featuredOnly = false;
    public $sortBy = 'created_at'; //default sorting option
    public $sortDirection = 'desc'; // default sorting direction
    public $viewMode = 'grid'; // default view mode


    // Synchronize properties with the browser
    // show query string,...except when it is empty
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'listingType' => ['except' => ''],
        'city' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'minBedroom' => ['except' => ''],
        'featuredOnly' => ['except' => false],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],

    ];

    public function updatingImgSearch(): void{
        $this->resetPage();
    }

    public function updatingType(): void{
        $this->resetPage();
    }

    public function updatingListingType(): void{
        $this->resetPage();
    }
    
    public function updatingCity(): void{
        $this->resetPage();
    }

    public function updatingMinPrice(): void{
        $this->resetPage();
    }

    public function updatingMaxPrice(): void{
        $this->resetPage();
    }

    public function updatingFeaturedOnly(): void{
        $this->resetPage();
    }

    
    // Automatically updates when search inputs change
    public function updated() {
        // no need to manually refresh, livewire does it automatically
    }

    // Sorting
    public function SortBy($field): void{
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    // Clear Filters
    public function clearFilters(): void {
        $this->reset(['search', 'minPrice', 'maxPrice', 'type','listingType','minBedrooms','featuredOnly','sortBy','sortDirection']);
        $this->resetPage();
    }

    // Change view mode [grid or list]
    public function setViewMode($mode): void{
        $this->viewMode = $mode;
    }

    // Computed properties
    #[Computed()] // create derived properties

    public function properties(): Builder{
        return Property::query()
        ->available()
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate(6);
    }

    public function render()
    {
        return view('livewire.property-listing', [
            'properties' => Property::when($this->search,
            function($query) {
                return $query->where('title','like', '%{$this->search}%');
            })->get()
        ]);
    }
}
