<?php

namespace App\Http\Requests;

use App\Rules\BookingDateAvailable;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:50'],
            'booking_date'  => ['required', 'date', new BookingDateAvailable],
            'service_type'  => ['required', 'string', 'max:255'],
            'notes'         => ['nullable', 'string'],
            'status'        => ['nullable', 'in:Pending,Confirmed,Cancelled'],
        ];
    }
}
