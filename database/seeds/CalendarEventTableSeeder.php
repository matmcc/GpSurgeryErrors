<?php

use App\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CalendarEventTableSeeder extends Seeder {

    public function run()
    {
        // logic checks for existing events - factory class defeated this ...
        // ... perhaps creating all events and then inserting into DB - so not in DB at time of check

        // TODO: check for clash only checks if another event exists which clashes
        // TODO: ... needs to check if clashing event has same admin

        for ($i=0; $i<200; $i++) {

            $faker = Faker\Factory::create();
            $user = \App\User::inRandomOrder()->first();
            $admin = \App\Admin::bookable()->inRandomOrder()->first();

            // do-while generates datetime as Carbon, checks against hardcoded opening hours
            do {
                // turn faker datetime into Carbon
                $start = Carbon::instance($faker->dateTimeInInterval('-7 days', '+ 30 days'));
                // set minutes to 0 or 30
                $start->minute = $start->minute < 30 ? $start->minute = 0 : $start->minute = 30;
                // copy into end - otherwise pass by reference
                $end = $start->copy()->addMinutes(29);

            }
                // checks: if weekend, if without opening hours, if clashes with existing event
            while ($start->isWeekend() || $start->hour < 8 || $end->hour > 17 || CalendarEvent::between($start, $end)->exists());

            // create and save event
            $eventInfo = [
                'title' => "Appointment with $admin->job_title $admin->name",
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'start' => $start,
                'end' => $end,
            ];

            //$e = new Event($eventInfo);
            //$e->save();
            CalendarEvent::create($eventInfo);
        }
    }

}