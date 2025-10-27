{{-- Background for light and dark theme --}}
<div class="min-h-sreen bg-gray-50 dark:bg-neutral-900">
    
    {{-- Header Section --}}
    <div class="bg-white dark:bg-gray-800 shadow sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Find Your Dream Property</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Discover the best real estate opportunities in Nigeria.</p>
            </div>
        </div>
    </div>


    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Filters Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                {{-- Search --}}
                <div>
                    {{-- Adjusted label text for better contrast in light mode --}}
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" >Search</label>
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search properties"
                    
                    {{--  NB: debounce.500ms = Wait 500ms after typing stops before updating --}}
                    {{-- Added text ccolor for both modes and dark border color --}}
                    class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>


                {{-- Property Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Property Type</label>
                    
                    <select wire:model.live="type" 
                    {{-- "focus:border-blue-500 focus:ring-blue-500" -> Cool feature! When user clicks the dropdown, it gets a blue border and glow --}}
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        
                        <option value="">All Types</option>
                        @foreach($propertyTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>                
                </div>

                
                {{-- Listing Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Listing Type</label>
                    <select wire:model.live="listingType" 
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        
                        <option value="">For Sale & Rent</option>
                        @foreach($listingTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                
                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                    <select wire:model.live="city" 
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Cities</option>
                        @foreach($cities as $cityOption)
                            <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- Advanced Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Price Range --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Price (NGN)</label>
                    <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Min price"
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Price (NGN)</label>
                    <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Max price"
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>


                {{-- Min Bedrooms --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Bedrooms</label>
                    <select wire:model.live="minBedrooms" 
                        class="w-full text-gray-900 dark:text-gray-200 dark:bg-gray-700 p-2 rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        
                        <option value="">Any</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">{{ $i }}+ bedroom{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>


                {{-- Featured Toggle --}}
                <div class="flex items-end">
                    <label class="flex items-center">
                        {{-- Added dark border to checkbox --}}
                        <input type="checkbox" wire:model.live="featuredOnly" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Featured only</span>
                    </label>
                </div>
            </div>


            {{-- Actions --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="clearFilters" class="bg-gray-600 text-white px-6 py-1 rounded-md hover:bg-blue-700 transition-colors duration-300 font-medium">
                    Clear All Filters
                </button>
                
                <div class="flex items-center space-x-4">

                    {{-- View Mode Toggle --}}
                    <div class="flex items-center space-x-2">
                        {{-- Grid mode --}}
                        <button wire:click="setViewMode('grid')" 
                            class="p-2 rounded-md {{ $viewMode === 'grid' ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-200' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>

                        {{-- List mode --}}
                        <button wire:click="setViewMode('list')"
                            class="p-2 rounded-md {{ $viewMode === 'list' ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-200' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>


            </div>
        </div>

    
    
    </div>

</div>