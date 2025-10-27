{{-- resources/views/livewire/propertylisting.blade.php --}}
<div>
    {{-- Search and Filters Section --}}
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Search Bar --}}
            <div class="mb-6">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search by title, location, or description..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            {{-- Filter Controls --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                {{-- Price Range --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                    <input 
                        type="number" 
                        wire:model.live="minPrice"
                        placeholder="Min price"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                    <input 
                        type="number" 
                        wire:model.live="maxPrice"
                        placeholder="Max price"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    >
                </div>

                {{-- Bedrooms/Bathrooms --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                    <select wire:model.live="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Any</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                    <select wire:model.live="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Any</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                    </select>
                </div>
            </div>

            {{-- Active Filters --}}
            @if($hasFilters)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        @if($search)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Search: "{{ $search }}"
                                <button wire:click="$set('search', '')" class="ml-1 hover:text-blue-600">×</button>
                            </span>
                        @endif
                        @if($minPrice)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                Min: ${{ number_format($minPrice) }}
                                <button wire:click="$set('minPrice', null)" class="ml-1 hover:text-green-600">×</button>
                            </span>
                        @endif
                        {{-- Add more filter tags as needed --}}
                    </div>
                    <button 
                        wire:click="resetFilters"
                        class="text-sm text-red-600 hover:text-red-800"
                    >
                        Clear all filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Results Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Properties for Sale</h2>
                <p class="text-gray-600 mt-1">
                    @if($properties->total() > 0)
                        Showing {{ $properties->firstItem() }} - {{ $properties->lastItem() }} of {{ $properties->total() }} properties
                    @else
                        No properties found
                    @endif
                </p>
            </div>

            {{-- Sort Controls --}}
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-700">Sort by:</span>
                <div class="flex space-x-2">
                    <button 
                        wire:click="SortBy('price')"
                        class="px-3 py-2 text-sm rounded-md {{ $sortBy === 'price' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}"
                    >
                        Price 
                        @if($sortBy === 'price')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </button>
                    <button 
                        wire:click="SortBy('created_at')"
                        class="px-3 py-2 text-sm rounded-md {{ $sortBy === 'created_at' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}"
                    >
                        Newest
                        @if($sortBy === 'created_at')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </button>
                    <button 
                        wire:click="SortBy('bedrooms')"
                        class="px-3 py-2 text-sm rounded-md {{ $sortBy === 'bedrooms' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}"
                    >
                        Bedrooms
                        @if($sortBy === 'bedrooms')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Properties Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        {{-- Property Image --}}
                        <div class="relative h-48 bg-gray-200">
                            @if($property->images->count() > 0)
                                <img 
                                    src="{{ $property->images->first()->url }}" 
                                    alt="{{ $property->title }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- Favorite Button --}}
                            <button class="absolute top-2 right-2 p-2 bg-white rounded-full shadow-md hover:bg-gray-100">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Property Details --}}
                        <div class="p-4">
                            {{-- Price --}}
                            <div class="mb-2">
                                <span class="text-2xl font-bold text-gray-900">
                                    ${{ number_format($property->price) }}
                                </span>
                            </div>

                            {{-- Address --}}
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $property->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $property->address }}</p>

                            {{-- Property Features --}}
                            <div class="flex items-center justify-between text-sm text-gray-600 border-t border-gray-100 pt-3">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        {{ $property->bedrooms }} bed
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $property->bathrooms }} bath
                                    </span>
                                </div>
                                
                                {{-- Square Footage if available --}}
                                @if($property->square_feet)
                                    <span>{{ number_format($property->square_feet) }} sq ft</span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-4 flex space-x-2">
                                <a 
                                    href="{{ route('properties.show', $property) }}"
                                    class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 transition-colors"
                                >
                                    View Details
                                </a>
                                <button 
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                                >
                                    Contact
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $properties->links() }}
            </div>

        @else
            {{-- No Results --}}
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Try adjusting your search filters or browse all properties.
                </p>
                <div class="mt-6">
                    <button 
                        wire:click="resetFilters"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Clear all filters
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading properties...</p>
        </div>
    </div>
</div>