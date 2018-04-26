<?php

use App\Admin;
use App\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DayOffToCalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $daysOff = $admin->daysOff()->get()->pluck('day');
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            $now = new Carbon();
            $begin = $now->startOfWeek()->subWeek(); //1st day of week (Sun) 1 week ago
            $weeksLeftInYear = 52 - $begin->weekOfYear;
            for ($weeksLeftInYear; $weeksLeftInYear > 0; $weeksLeftInYear--) {
                // for each week left in the year, for each day off
                foreach ($daysOff as $day) {
                    $start = $begin->copy();
                    $end = $begin->copy();
                    $eventInfo = [
                        'title' => "Busy: $admin->name",
                        'user_id' => 1,
                        'admin_id' => $admin->id,
                        'start' => $start->addDays($day)->addHours(8),  // current open and close times BEWARE
                        'end' => $end->addDays($day)->addHours(18)
                    ];
                    CalendarEvent::create($eventInfo);

                }
                // increment week
                $begin = $begin->addWeek();
            }
        }
    }
}
