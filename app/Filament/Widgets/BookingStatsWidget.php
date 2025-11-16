<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Bookings', Booking::count())
                ->description('جميع الحجوزات')
                ->color('primary'),

            Stat::make('Pending', Booking::where('status', 'Pending')->count())
                ->description('في انتظار التأكيد')
                ->color('warning'),

            Stat::make('Confirmed', Booking::where('status', 'Confirmed')->count())
                ->description('مؤكد')
                ->color('success'),

            Stat::make('Cancelled', Booking::where('status', 'Cancelled')->count())
                ->description('ملغي')
                ->color('danger'),
        ];
    }
}
