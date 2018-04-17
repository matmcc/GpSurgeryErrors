<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{

    /**
     * Creates RGB hex code for html colour based on $this->name
     * @return string
     */
    protected function setColourRgb($input)
    {
        return '#'.substr(md5($input), 0, 6);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $receptionist = new \App\Admin([
            'name' => 'John Smith',
            'email' => 'John.Smith@OverSurgery.co.uk',
            'job_title' => 'admin',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('John Smith')
        ]);
        $receptionist->save();

        $drOne = new \App\Admin([
            'name' => 'Charles Bovary',
            'email' => 'Charles.Bovary@OverSurgery.co.uk',
            'job_title' => 'dr',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Charles Bovary')
        ]);
        $drOne->save();

        $drTwo = new \App\Admin([
            'name' => 'Dick Diver',
            'email' => 'Dick.Diver@OverSurgery.co.uk',
            'job_title' => 'dr',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Dick Diver')
        ]);
        $drTwo->save();

        $drThree = new \App\Admin([
            'name' => 'John Watson',
            'email' => 'John.Watson@OverSurgery.co.uk',
            'job_title' => 'dr',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('John Watson')
        ]);
        $drThree->save();

        $drFour = new \App\Admin([
            'name' => 'Henry Jeckyll',
            'email' => 'Henry.Jeckyll@OverSurgery.co.uk',
            'job_title' => 'dr',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Henry Jeckyll')
        ]);
        $drFour->save();

        $nurseOne = new \App\Admin([
            'name' => 'Margaret Sanger',
            'email' => 'Margaret.Sanger@OverSurgery.co.uk',
            'job_title' => 'nurse',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Margaret Sanger')
        ]);
        $nurseOne->save();

        $nurseTwo = new \App\Admin([
            'name' => 'Christine Beasley',
            'email' => 'Christine.Beasley@OverSurgery.co.uk',
            'job_title' => 'nurse',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Christine Beasley')
        ]);
        $nurseTwo->save();

        $nurseThree = new \App\Admin([
            'name' => 'Janet Davies',
            'email' => 'Janet.Davies@OverSurgery.co.uk',
            'job_title' => 'nurse',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Janet Davies')
        ]);
        $nurseThree->save();

        $nurseFour = new \App\Admin([
            'name' => 'Jackie Smith',
            'email' => 'Jackie.Smith@OverSurgery.co.uk',
            'job_title' => 'nurse',
            'password' => \Hash::make('password'),
            'remember_token' => str_random(60),
            'color' => $this->setColourRgb('Jackie Smith')
        ]);
        $nurseFour->save();

    }
}
