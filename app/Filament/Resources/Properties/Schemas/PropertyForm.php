<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
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
                    ->prefix('#'),
                TextInput::make('price_per_sqft')
                    ->numeric(),
                Textarea::make('address')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('Nigeria'),
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
                    ->numeric()
                    ->prefix('sqft'),

                TextInput::make('built_year')
                    ->numeric(),
                Toggle::make('furnished')
                ->required(),

                Toggle::make('parking')
                ->live()
                ->required(),

                TextInput::make('parking_spaces')
                    ->numeric()
                    ->visible(condition:fn(Get $get): bool => $get('parking')),
                
                TagsInput::make('features')
                ->helperText('Add features like garden, pool, gym, etc')
                ->columnSpanFull(),

                FileUpload::make('images')
                ->multiple()
                ->image()
                ->maxFiles(count: 10)
                ->disk(name: 'public')
                ->directory(directory:'properties-images')
                ->columnSpanFull(),

                TextInput::make('slug')
                ->readOnly(),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),

                Toggle::make('is_featured')
                    ->live()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('featured_until')
                    ->visible(condition:fn(Get $get): bool => $get('is_featured')),
    
                    TextInput::make('contact_name'),
                TextInput::make('contact_phone')
                    ->tel(),
                TextInput::make('contact_email')
                    ->email(),
            ]);
    }
}
