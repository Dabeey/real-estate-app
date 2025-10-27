<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use illuminate\Database\Eloquent\Builder;
use illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\callback;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'listing_type',
        'status',
        'price',
        'price_per_sqrt',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'total_area',
        'built_year',
        'furnished',
        'parking',
        'parking_spaces',
        'features',
        'images',
        'slug',
        'meta title',
        'meta_description',
        'is_featured',
        'is_active',
        'featured_until',
        'contact_name',
        'contact_phone',
        'contact_email',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'furnished' => 'boolean',
        'parking' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'featured_until' => 'datetime',
        'price' => 'decimal:2',
        'price_per_sqrt' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(callback: function ($property): void {
            if (empty($property->slug)) {
            $property->slug = Str::slug($property->title);
            }
        });

        static::updating(callback: function ($property): void {
            // Update slug if title changes
            if ($property->isDirty('title')) {
                $property->slug = Str::slug($property->title);
            }
        });
    }

    // Route model binding by slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Scopes for filtering
    #[Scope]
    public function available(Builder $query):Builder {
        return $query->where(column: 'status', operator: 'available')
        -> where(column: 'is_active', operator: true);
    }

    #[Scope]
    public function forSale(Builder $query): Builder {
        return $query->where(column: 'listing_type', operator:'sale');
    }

    #[Scope]
    public function forRent(Builder $query): Builder
    {
        return $query->where(column: 'listing_type', operator: 'rent');
    }

    #[Scope]
    public function featured(Builder $query): Builder
    {
        return $query->where(column: 'is_featured', operator: true)
        ->where(column:'is_active', operator:true)
        ->where(column:'featured_until', operator: '>=', value:now());
    }

    #[Scope]
    public function inCity(Builder $query, string $city): Builder
    {
        return $query->where(column: 'city', operator: 'like', value: '%{$city}%');
    }

    #[Scope]
    public function priceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween(column: 'price', values: [$min, $max]);
    }

    #[Scope]
    public function byType(Builder $query, string $type): Builder
    {
        return $query->where(column: 'type', operator: $type);
    }

    #[Scope]
    public function withBedrooms(Builder $query, int $count): Builder
    {
        return $query->where(column: 'bedroom', operator: '>=', value: $count);
    }

    // Accessor methods
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' NGN';
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state}, {$this->country}";
    }

    public function getMainImageAttribute(): ?string
    {
        // If images is a JSON column, decode and return the first image
        $images = $this->images ?? '[]';
        return $images[0] ?? null;

        // $images=$this->images;
        // return $images && count(value: $images) > 0? $images[0] : null;
    }

    public function getImageUrlAttribute(): ?string
    {
        // Build a full URL to the main image 
        $mainImage = $this->main_image;
        return $mainImage ? storage::url($mainImage): null;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'success',
            'sold' => 'danger',
            'pending' => 'info',
            'draft' => 'secondary',
            'rented' => 'warning',
            default => 'secondary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'apartment' => 'fa-solid fa-building',
            'house' => 'fa-solid fa-house',
            'condo' => 'fa-solid fa-city',
            'land' => 'fa-solid fa-tree',
            'townhouse' => 'fa-solid fa-house-chimney',
            'villa' => 'fa-solid fa-hotel',
            'commercial' => 'fa-solid fa-store',
            default => 'fa-solid fa-home',
        };
    }

    // Helper methods

    public function isFeatured(): bool
    {
        return $this->is_featured === true;
    }


    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }

    public function calculatePricePerSqft(): ?float
    {
        if (! $this->total_area || $this->total_area <= 0) {
            return null;
        }
    
        $pricePerSqft = round($this->price / $this->total_area, 2);
        $this->price_per_sqft = $pricePerSqft;
        $this->save();
    
        return $pricePerSqft;
    }
    

    public function addFeature(string $feature): void
    {
        $features = $this->features ?? [];

        if (! in_array($feature, $features, true)) {
            $features[] = $feature;
            $this->features = $features;
            $this->save();
        }
    }

    public function removeFeature(string $feature): void
    {
        if (! $this->features) {
            return;
        }

        $features = array_filter($this->features, fn ($f) => $f !== $feature);
        $this->features = array_values($features);
        $this->save();
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? [], true);
    }

    // Static Method
    public static function getPropertyTypes(): array
    {
        return [
            'apartment' => 'Apartment',
            'house' => 'House',
            'condo' => 'Condo',
            'land' => 'Land',
            'townhouse' => 'Townhouse',
            'villa' => 'Villa',
            'commercial' => 'Commercial Property',
        ];
    }

    public static function getListingTypes(): array
    {
        return [
            'sale' => 'For Sale',
            'rent' => 'For Rent',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'available' => 'Available',
            'sold' => 'Sold',
            'pending' => 'Pending',
            'draft' => 'Draft',
            'rented' => 'Rented',
        ];
    }


    

}
