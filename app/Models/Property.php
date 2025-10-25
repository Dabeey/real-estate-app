<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use illuminate\Database\Eloquent\Builder;
use illuminate\Database\Eloquent\Attributes\Scope;

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

}
