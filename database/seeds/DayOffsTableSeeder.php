<?php

use App\DayOff;
use Illuminate\Database\Seeder;

class DayOffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [0, 1, 2, 3, 4, 5, 6];
        foreach ($days as $day) {
            $d = new DayOff(['day' => $day]);
            $d->save();
        }

    }
}
