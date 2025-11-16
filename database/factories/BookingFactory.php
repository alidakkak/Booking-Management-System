<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;
    public function definition(): array
    {
        $bookingDate = $this->faker->dateTimeBetween('+1 days', '+30 days');
        return [
            'customer_name' => $this->faker->name(),
            'phone'         => '05' . $this->faker->numerify('########'),
            'booking_date'  => $bookingDate,
            'service_type'  => $this->faker->randomElement([
                'Haircut',
                'Beard Trim',
                'Hair Color',
                'Massage',
            ]),
            'notes'         => $this->faker->boolean(40) ? $this->faker->sentence() : null,
            'status'        => $this->faker->randomElement(['Pending', 'Confirmed', 'Cancelled']),
        ];
    }
}
