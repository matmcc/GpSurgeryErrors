<?php

namespace App\Http\Controllers;

use App\Admin;
use App\CalendarEvent;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Calendar;

/**
 * TODOS
 * Todo: Add validation to update
 * Todo: build forms for edit, view
 * Todo: add email notifications
 *
 * Todo: Admin view - what is needed?
 * Todo: Admin: table of todays events? Filtered by admin
 * Todo: Admin: filter events by user
 * Todo: Admin: helper to search user by email, name, etc?
 *
 * Todo: DONE add faker color to admins DONE - could pick nicer colours
 * Todo: DONE add colours to Calendar events DONE
 * Todo: add name logic for title == null
 * Todo: ... then remove names from seeder
 *
 * Todo: add prescription model, v, c
 * Todo: add results model, v, c
 *
 * Todo: add Parsley JS validation to registration form and other inputs
 *
 * Todo: add fullcalendar callbacks to update events if dragged
 * Todo: add fc callbacks for times
 * Todo: add fc draggable to create event ?
 *
 * Todo: test bootstrap for resizeable design
 *
 * Todo: Build front page, contacts page,
 * Todo: Chat?
 *
 * Class CalendarEventController
 * @package App\Http\Controllers
 */
class CalendarEventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web,admin');
    }

    public function eventsByAdmin(Admin $admin_id)
    {
        return $admin_id->events()->get();
    }

    public function eventsByUser(User $user_id)
    {
        return $user_id->events()->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $calendar_events = CalendarEvent::all();

        // TODO: Build Calendar class for options and docs
        $calendarOptions = [
            'header' => ['left' => 'prev,next today', 'center' => 'title', 'right' => 'month,agendaWeek,agendaDay'],
            'defaultView' => 'agendaWeek',
            'weekends' => false,
            'slotDuration' => '00:30:00',
            'minTime' => '08:00:00',
            'maxTime' => '18:00:00',
            'weekNumbers' => true,
            'navLinks' => true,
            'locale' => 'en-gb'
        ];

        $calendar = Calendar::addEvents($calendar_events)->setOptions($calendarOptions);

        $admins = Admin::all()->pluck('name', 'id');

        return view('calendar_events.index', compact('calendar_events', 'calendar', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('calendar_events.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function createByAdmin(Request $request)
    {

        $calendarOptions = [
            'header' => ['left' => 'prev,next today', 'center' => 'title', 'right' => 'month,agendaWeek,agendaDay'],
            'defaultView' => 'agendaDay',
            'weekends' => false,
            'slotDuration' => '00:30:00',
            'minTime' => '08:00:00',
            'maxTime' => '18:00:00',
            'weekNumbers' => true,
            'navLinks' => true,
            'locale' => 'en-gb'
        ];

        $admin_id = $request->input('selectAdmin');
        $admins = Admin::all()->pluck('name', 'id');
        $calendar_events = CalendarEvent::where('admin_id', $admin_id)->get();
        //$calendar_events = route("calendar_events.events.admin", $admin_id);

        // TODO: Currently provides range of times for all days - needs to be per day
        $disabledTimeRanges = [];
        foreach ($calendar_events as $k => $event) {
            $start = $event->start->toTimeString();
            $end = $event->end->toTimeString();
            $disabledTimeRanges[] = [$event->start->toDateString(), [substr("$start", 0, 5), substr("$end", 0, 5)]];
        }
        //dd($disabledTimeRanges);
        \JavaScript::put(['disabledTimes' => [$disabledTimeRanges], 'admin' => $admin_id]);
        //dd($disabledTimeRanges);

        $calendar = Calendar::addEvents($calendar_events)->setOptions($calendarOptions);

        return view('calendar_events.create', compact('calendar', 'disabledTimeRanges', 'admin_id', 'admins'));
    }

    /**
     * @param Request $request
     * @param CalendarEvent $calendar_event
     */
    public function saveCalendarEvent(Request $request, CalendarEvent $calendar_event): void
    {
        $calendar_event->title          = $request->input("title");
        $calendar_event->start          = Carbon::parse($request->input("start"));
        //$calendar_event->end          = Carbon::parse($request->input("end"));
        $calendar_event->end            = Carbon::parse($request->input("start"))->addMinutes(30);
        //$calendar_event->is_all_day       = $request->input("is_all_day");
        //$calendar_event->background_color = $request->input("background_color");
        $calendar_event->user_id        = Auth::user()->getAuthIdentifier();
        $calendar_event->admin_id       = $request->input('selectAdmin');

        $calendar_event->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\StoreCalendarEvent $request
     * @return Response
     */
    public function store(Requests\StoreCalendarEvent $request)
    {
        //dd($request);
        $calendar_event = new CalendarEvent();
        // Validation in type-hinted form request
        $this->saveCalendarEvent($request, $calendar_event);

        return redirect()->route('calendar_events.index')->with('message', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param CalendarEvent $calendar_event
     * @return Response
     */
    public function show(CalendarEvent $calendar_event)
    {
        return view('calendar_events.show', compact('calendar_event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CalendarEvent $calendar_event
     * @return Response
     */
    public function edit(CalendarEvent $calendar_event)
    {
        return view('calendar_events.edit', compact('calendar_event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CalendarEvent $calendar_event
     * @return Response
     */
    public function update(Request $request, CalendarEvent $calendar_event)
    {
        $this->saveCalendarEvent($request, $calendar_event);

        return redirect()->route('calendar_events.index')->with('message', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CalendarEvent $calendar_event
     * @return Response
     * @throws \Exception
     */
    public function destroy(CalendarEvent $calendar_event)
    {
        $calendar_event->delete();

        return redirect()->route('calendar_events.index')->with('message', 'Item deleted successfully.');
    }

}
