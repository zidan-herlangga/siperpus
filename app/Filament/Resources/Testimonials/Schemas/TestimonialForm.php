<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_id')
                    ->label('ID Siswa')
                    ->required()
                    ->disabled()
                    ->numeric(),
                Textarea::make('content')
                    ->label('Testimoni')
                    ->required()
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(5),
                Toggle::make('is_approved')
                    ->default(false)
                    ->live()
                    ->required(),
            ]);
    }
}
