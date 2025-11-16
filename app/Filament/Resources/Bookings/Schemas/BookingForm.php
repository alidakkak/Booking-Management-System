<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(1)->schema([
            Forms\Components\TextInput::make('customer_name')
                ->required()
                ->label('Customer Name'),

            Forms\Components\TextInput::make('phone')
                ->required()
                ->label('Phone Number'),

            Forms\Components\DateTimePicker::make('booking_date')
                ->required()
                ->label('Booking Date'),

            Forms\Components\TextInput::make('service_type')
                ->required()
                ->label('Service Type'),

            Forms\Components\Textarea::make('notes')
                ->label('Notes'),

            Forms\Components\Select::make('status')
                ->options([
                    'Pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                    'Cancelled' => 'Cancelled',
                ])
                ->default('Pending')
                ->required()
                ->label('Status'),
        ]);
    }
}
