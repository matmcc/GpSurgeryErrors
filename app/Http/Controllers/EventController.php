<?php

namespace App\Http\Controllers;

use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;
use App\Event;

use Calendar;

class EventController extends Controller
{
    public function index()
    {
        $admins = Admin::pluck('name', 'id');

        $events = [];
        $data = Event::all();
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = Calendar::event(
                    $value->event_name,
                    false,
                    new \DateTime($value->start_datetime),
                    new \DateTime($value->end_datetime),
                    null,
                    // Add color and link on event
                    [
                        'color' => '#f05050',
                        'url' => 'localhost:8000/events#modal',
                    ]
                );
            }
        }

        $calendarOptions = [
            'header' => ['left' => 'prev,next today', 'center' => 'title', 'right' => 'month,agendaWeek,agendaDay'],
            'defaultView' => 'agendaWeek',
            'weekends' => false,
            'slotDuration' => '00:30:00',
            'minTime' => '08:00:00',
            'maxTime' => '18:00:00',
            'weekNumbers' => true,
            'navLinks' => true
            ];

        $calendar = Calendar::addEvents($events)->setOptions($calendarOptions);
        return view('events', compact('calendar', 'admins'));
    }

    public function demo()
    {
        $events = [];
        $data = Event::all();
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = Calendar::event(
                    $value->event_name,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date.' +1 day'),
                    null,
                    // Add color and link on event
                    [
                        'color' => '#f05050',
                        'url' => 'localhost:8000/events#modal'
                    ]
                );
            }
        }
        $calendar = Calendar::addEvents($events);
        return view('events_demo', compact('calendar'));
    }

    public function addEvent(Request $request)
    {
        //dd($request);

        $validator = Validator::make($request->all(), [
            'event_name' => 'required',
            'start_datetime' => 'required',
            'selectBookable' => 'required',
        ]);

        if ($validator->fails()) {
            \Session::flash('warning', 'Please enter valid event details');
            return Redirect::to('/events')->withInput()->withErrors($validator);
        }

        $event = new Event;
        $event->event_name = $request['event_name'];
        $event->start_datetime = $request['start_datetime'];
        $event->end_datetime = Carbon::parse($request['start_datetime'])->addMinutes(30);
        $event->user_id = Auth::user()->getAuthIdentifier();
        $event->admin_id = $request['selectBookable'];
        $event->save();

        \Session::flash('success', 'Event added successfully.');
        return Redirect::to('/events');
    }
}
