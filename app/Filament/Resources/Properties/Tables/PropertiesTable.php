<?php

namespace App\Filament\Resources\Properties\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('listing_type'),
                TextColumn::make('status'),

                TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state, 2))
                    ->money(currency:'NGN')
                    ->sortable(),
                TextColumn::make('price_per_sqft')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state, 2))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),

                // Hidden by default
                TextColumn::make('state')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postal_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_area')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('built_year')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('furnished')
                    ->boolean(),
                IconColumn::make('parking')
                    ->boolean(),
                TextColumn::make('parking_spaces')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('features')
                    ->formatStateUsing(function ($state) {
                        if (empty($state) || $state === 'null' || $state === '[]') {
                            return '-';
                        }
                        
                        $features = json_decode($state, true);

                          // If json_decode failed or returned null
                        if (!is_array($features) || json_last_error() !== JSON_ERROR_NONE) {
                            return '-';
                        }
                        
                        // If array is empty
                        if (empty($features)) {
                            return '-';
                        }
                        
                        return implode(', ', $features);
                    })
                    ->badge()
                    ->colors(['primary'])
                    ->limit(50), // Show first 3 features

                ImageColumn::make('images')
                    ->getStateUsing(function ($record) {
                        $images = $record->images ?? [];
                        return !empty($images) ? $images[0] : null;
                    })
                    ->circular()
                    ->imageSize(50)
                    ->defaultImageUrl('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=50&h=50&q=80')
                    ->extraImgAttributes(['class' => 'object-cover']),

                // ImageColumn::make('images')
                //     ->getStateUsing(function ($record) {
                //         return $record->images ?? [];
                //     })
                //     ->stacked()
                //     ->limit(3)
                //     ->circular()
                //     ->size(30)
                //     ->overlap(0.5),
            

                // Hidden by default
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_featured')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('featured_until')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('contact_name')
                    ->searchable(),
                TextColumn::make('contact_phone')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
