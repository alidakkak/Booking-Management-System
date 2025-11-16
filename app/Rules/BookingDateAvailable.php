<?php

namespace App\Rules;

use App\Models\Booking;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BookingDateAvailable implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // تحويل القيمة إلى Carbon بشكل موحّد
            $bookingDate = Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $fail('Invalid date format.');
            return;
        }

        // فحص وجود أي حجز بنفس الوقت
        $exists = Booking::where('booking_date', $bookingDate)->exists();

        if ($exists) {
            $fail('This booking time is already taken.');
        }
    }
}
