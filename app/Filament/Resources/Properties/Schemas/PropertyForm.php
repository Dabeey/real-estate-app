<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
            'apartment' => 'Apartment',
            'house' => 'House',
            'condo' => 'Condo',
            'land' => 'Land',
            'townhouse' => 'Townhouse',
            'villa' => 'Villa',
            'commercial' => 'Commercial',
        ])
                    ->required(),
                Select::make('listing_type')
                    ->options(['sale' => 'Sale', 'rent' => 'Rent'])
                    ->default('sale')
                    ->required(),
                Select::make('status')
                    ->options([
            'available' => 'Available',
            'sold' => 'Sold',
            'pending' => 'Pending',
            'draft' => 'Draft',
            'rented' => 'Rented',
        ])
                    ->default('available')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('price_per_sqft')
                    ->numeric(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('Tanzania'),
                TextInput::make('postal_code'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('bedrooms')
                    ->numeric(),
                TextInput::make('bathrooms')
                    ->numeric(),
                TextInput::make('total_area')
                    ->numeric(),
                TextInput::make('built_year')
                    ->numeric(),
                Toggle::make('furnished'),
                Toggle::make('parking'),
                TextInput::make('parking_spaces')
                    ->numeric(),
                TextInput::make('features'),
                TextInput::make('images'),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('featured_until'),
                TextInput::make('contact_name'),
                TextInput::make('contact_phone')
                    ->tel(),
                TextInput::make('contact_email')
                    ->email(),
            ]);
    }
}
