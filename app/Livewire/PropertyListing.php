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
    public $city = ''; // ← ADDED THIS
    public $minBedrooms = ''; // ← FIXED SPELLING
    public $featuredOnly = false;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'grid';

    // Add these arrays for your dropdowns
    public $propertyTypes = [ // ← ADDED THIS
        'house' => 'House',
        'apartment' => 'Apartment',
        'condo' => 'Condo',
        'villa' => 'Villa',
        'commercial' => 'Commercial',
        'land' => 'Land',
    ];

    public $cities = [ // ← ADDED THIS
        'Lagos', 'Abuja', 'Port Harcourt', 'Kano', 'Ibadan'
    ];

    public $listingTypes = [ // ← ADDED THIS
        'sale' => 'For Sale',
        'rent' => 'For Rent'
    ];

    // Synchronize properties with the browser
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'listingType' => ['except' => ''],
        'city' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'minBedrooms' => ['except' => ''], // ← FIXED: minBedrooms (not minBedroom)
        'featuredOnly' => ['except' => false],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    // Reset page when filters change
    public function updatingSearch(): void{ // ← FIXED: updatingSearch (not updatingImgSearch)
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

    public function updatingMinBedrooms(): void{ // ← ADDED THIS
        $this->resetPage();
    }

    public function updatingFeaturedOnly(): void{
        $this->resetPage();
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
        $this->reset([
            'search', 'minPrice', 'maxPrice', 'type', 
            'listingType', 'city', 'minBedrooms', 'featuredOnly'
        ]);
        $this->resetPage();
    }

    // Change view mode
    public function setViewMode($mode): void{
        $this->viewMode = $mode;
    }

    // Computed property for properties
    #[Computed]
    public function properties()
    {
        return Property::query()
            ->when($this->search, function(Builder $query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%') // ← FIXED: string concatenation
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('address', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->listingType, fn($q) => $q->where('listing_type', $this->listingType))
            ->when($this->city, fn($q) => $q->where('city', $this->city))
            ->when($this->minPrice, fn($q) => $q->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn($q) => $q->where('price', '<=', $this->maxPrice))
            ->when($this->minBedrooms, fn($q) => $q->where('bedrooms', '>=', $this->minBedrooms))
            ->when($this->featuredOnly, fn($q) => $q->where('is_featured', true))
            ->available()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(6);
    }

    public function render()
    {
        return view('livewire.property-listing');
    }
}