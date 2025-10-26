<?php

namespace App\Filament\Resources\Properties\Schemas;

use App\Models\Property;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 1. Section For Basic Informations
                Section::make('Basic Information')
                ->columnSpanFull()
                    ->description('Provide the basic details of the property.')
                    ->columns(2)

                    ->schema(components: [
                        Grid::make(2)
                        ->columnSpanFull()

                            ->schema(components: [
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('slug')
                                    ->readOnly(),
                            ]),
                            Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                            // Another Grid
                            Grid::make(3)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('type')
                                ->options(Property::getPropertyTypes())
                                ->required(),

                                Select::make('listing_type')
                                ->options(Property::getListingTypes())
                                ->default('sale')
                                ->required(),
            
                            Select::make('status')
                                ->options(Property::getStatuses())
                                ->default('available')
                                ->required(),
            
                            ])
                        ]),
                    

                    // 2. Section for Pricing
                    Section::make('Pricing')

                    ->columnSpanFull()
                    ->description('Set the pricing details for the property.')
                    ->schema([
                        Grid::make(2)
                        ->columnSpanFull()

                        ->schema([
                            TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->prefix('₦'),
                            TextInput::make('price_per_sqft')
                                ->numeric()
                                ->prefix('NGN')
                                ->default(null),
                        ]),
                        
                    ]),


                    // 3. Section for the location

                    Section::make('Location')
                    ->columnSpanFull()
                    ->description('Provide the location details of the property')
                    ->schema([
                        Grid::make(2)
                        ->columnSpanFull()
                        ->schema([
                            Textarea::make('address')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('city')
                            ->required(),
                        TextInput::make('state')
                            ->required(),
                        TextInput::make('country')
                            ->required()
                            ->default('Nigeria'),
                        TextInput::make('postal_code'),
                        
                        ]),
                        
                        Grid::make(2)
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('latitude')
                            ->numeric(),
                        TextInput::make('longitude')
                            ->numeric(),
                        ]),
                    ]),


                    // 4. Section for property details
                    Section::make('Details')
                    ->columnSpanFull()
                    ->description('Additional details about the property.')
                    ->schema([
                        Grid::make(4)
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('bedrooms')
                            ->numeric(),
                        TextInput::make('bathrooms')
                            ->numeric(),
                        TextInput::make('total_area')
                            ->numeric()
                            ->prefix('sqft'),
                        TextInput::make('built_year')
                            ->numeric(),
                        ]),

                        Grid::make(2)
                        ->columnSpanFull()
                        ->schema([
                            Toggle::make('furnished')
                            ->required(),
            
                            Toggle::make('parking')
                            ->live()
                            ->required(),
                        ]),

                        Grid::make(2)
                        ->columnSpanFull()
                        ->schema([
                            TagsInput::make('features')
                            ->helperText('Add features like garden, pool, gym, etc'),
                            TextInput::make('parking_spaces')
                            ->numeric()
                            ->visible(condition:fn(Get $get): bool => $get('parking')),
                          
                        ]),
                        
                    ]),

                    
                    //  5. Section for media and SEO details
                    Section::make('Media & SEO')
                    ->columnSpanFull()
                    ->description('Upload images and set SEO details.')
                    ->schema([ 
                        FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->panelLayout('grid')
                            ->maxFiles(count: 10)
                            ->disk(name: 'public')
                            ->directory(directory:'properties-images')
                            ->columnSpanFull(),
                    
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('meta_title'),
                                Textarea::make('meta_description')
                                    ->columnSpanFull(),
                
                                Toggle::make('is_featured')
                                    ->live()
                                    ->required(),
                                Toggle::make('is_active')
                                ->default('active')
                                    ->required(),
                                DateTimePicker::make('featured_until')
                                    ->visible(condition:fn(Get $get): bool => $get('is_featured')),
                    
                        ]),
                ]),


                // 6. Section for contact
                Section::make('')
                    ->columnSpanFull()
                    ->description('')
                    ->schema([
                        TextInput::make('contact_name'),
                        TextInput::make('contact_phone')
                            ->tel(),
                        TextInput::make('contact_email')
                            ->email(),  
                        ]),
                    ]);
    }
}
