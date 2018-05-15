<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            MedicinesTableSeeder::class,
            AdminsTableSeeder::class,
            UsersTableSeeder::class,
            DayOffsTableSeeder::class,
            AdminDaysOffSeeder::class,
            DayOffToCalendarEventSeeder::class,
            CalendarEventTableSeeder::class
        );
    }
}
