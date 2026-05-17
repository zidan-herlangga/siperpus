<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Info siswa (readonly)
                Placeholder::make('student')
                    ->label('Dikirim oleh')
                    ->content(fn ($record) => $record?->student?->name ?? '-'),

                // 🔹 Isi testimoni (readonly)
                Textarea::make('content')
                    ->label('Testimoni')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),

                // 🔹 Rating (readonly)
                TextInput::make('rating')
                    ->label('Rating')
                    ->disabled()
                    ->dehydrated(false),

                // 🔹 Approval
                Toggle::make('is_approved')
                    ->label('Setujui Testimoni')
                    ->default(false)
                    ->required(),
            ]);
    }
}