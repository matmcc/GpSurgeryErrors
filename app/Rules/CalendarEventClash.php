<?php

namespace App\Rules;

use App\CalendarEvent;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class CalendarEventClash implements Rule
{
    protected $admin_id;

    /**
     * Create a new rule instance.
     *
     * @param $admin_id
     */
    public function __construct($admin_id)
    {
        $this->admin_id = $admin_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start = Carbon::parse($value);
        $end = Carbon::parse($value)->addMinutes(30);
        $q = CalendarEvent::where('admin_id', '=', $this->admin_id)->between($start, $end)->doesntExist();
        return $q;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This event clashes with another event. Please book at a different time';
    }
}
