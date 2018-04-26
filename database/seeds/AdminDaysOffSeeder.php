<?php

use App\Admin;
use Illuminate\Database\Seeder;

class AdminDaysOffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 10; $i++) {
            $a = Admin::find($i);
            $a->daysOff()->attach(1);
            $a->daysOff()->attach(7);
        }

        $a = Admin::find(2);
        $a->daysOff()->attach(2);
        $a = Admin::find(3);
        $a->daysOff()->attach(4);
        $a = Admin::find(5);
        $a->daysOff()->attach(6);
        $a = Admin::find(6);
        $a->daysOff()->attach(2);
        $a->daysOff()->attach(3);
        $a = Admin::find(7);
        $a->daysOff()->attach(2);
        $a->daysOff()->attach(3);
        $a = Admin::find(8);
        $a->daysOff()->attach(5);
        $a->daysOff()->attach(6);
        $a = Admin::find(9);
        $a->daysOff()->attach(5);
        $a->daysOff()->attach(6);
    }
}
