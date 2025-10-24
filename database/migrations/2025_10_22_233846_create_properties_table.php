<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            // basic information
            $table->string(column: 'title');
            $table->text(column: 'description');
            $table->enum(column: 'type', allowed: ['apartment', 'house', 'conda', 'land', 'townhouse', 'villa', 'commercial']);
            $table->enum(column: 'listing_type', allowed: ['sale', 'rent'])->default(value:'sale');
            $table->enum(column: 'status', allowed: ['available', 'sold', 'pending', 'draft', 'rented'])->default(value:'available');
            
            // Pricing
            $table->decimal(column: 'price', total:12, places:2);
            $table->decimal(column:'price_per_sqft', total: 8, places:2)->nullable();

            // Location
            $table->string(column: 'address');
            $table->string(column: 'city');
            $table->string(column: 'state');
            $table->string(column: 'country')->default(value:'Tanzania');
            $table->string(column: 'postal_code')->nullable();
            $table->decimal(column: 'latitude', total: 10, places:8)->nullable();
            $table->decimal(column: 'longitude', total: 10, places:8)->nullable();

            // Property details
            $table->integer(column: 'bedroom')->nullable();
            $table->integer(column: 'bathrooms')->nullable();
            $table->integer(column: 'total_area')->nullable();
            $table->integer(column: 'built_year')->nullable();
            $table->boolean(column: 'furnished')->nullable();
            $table->boolean(column: 'parking')->nullable();
            $table->integer(column: 'parking_spaces')->nullable();

            // Features (JSON for flexibility)
            $table->json(column:'features')->nullable();
            $table->json(column:'images')->nullable();

            // SEO
            $table->string(column: 'slug')->unique();
            $table->string(column: 'meta_title')->nullable();
            $table->text(column: 'meta_description')->nullable();
            $table->string(column: '')->nullable();

            $table->string(column: '')->nullable();

            // Visibility
            $table->boolean(column: 'is_featured')->default(value:false);
            $table->boolean(column: 'is_active')->default(value:true);
            $table->timestamp(column: 'featured_until')->nullable(value:false);

            // Contact Information
            $table->string(column: 'contact_')->nullable();
            $table->string(column: 'contact_phone')->nullable();
            $table->string(column: 'contact_email')->nullable();

            // Indexes
            $table->string(column: ['type', 'listing_type', 'status']);
            $table->string(column: ['city', 'state']);
            $table->string(column: ['price', 'listing_type']);
            $table->string(column: 'is_featured');
        

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
