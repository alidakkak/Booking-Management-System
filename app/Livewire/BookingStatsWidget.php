<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;

class BookingStatsWidget extends ChartWidget
{
    protected ?string $heading = 'Booking Stats Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
