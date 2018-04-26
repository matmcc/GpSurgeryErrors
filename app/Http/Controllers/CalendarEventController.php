<?php

namespace App\Http\Controllers;

use App\Admin;
use App\CalendarEvent;
use App\Http\Requests;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Calendar;

/**
 * TODOS
 * TODO: Prescription and Result - admin_id to enable functionality - e.g. contact
 * TODO: Edit for results does nothing
 *
 * Todo: Fix availability calendar in user view
 * TODO: ADD AJAX Call to Update calendar
 *
 * TODO: Validate patient has appt at same time?
 * Todo: Add validation to update
 * Todo: CHECK validation
 * Todo: CHECK Parsley JS validation for other inputs
 *
 * Note: show, edit, allow for additional features to be built, e.g. email re: event
 * Done-ish...
 * TODO: flash message for choose bookable
 * TODO: flash message for prescription renewal CHECK
 * Todo: Check flash messaging across all pages
 *
 * Todo: add email notifications
 * TODO: Tooltips ?
 *
 * TODO: Do we need days off for admins ?
 * TODO: Info page for drugs available and days off ?
 *
 * Todo: Admin links from each function to other function easily - keep User in session?
 * Todo: Admin Nav links are broken FIX or REMOVE?
 * Todo: Breadcrumb previous users for admin?
 *
 * Todo: DONE FOR USER test bootstrap for resizeable design
 * Todo: Check ARIA tags
 *
 * Todo: DONE add name logic for title == null
 * Todo: ... then remove names from seeder
 *
 * Todo: add fullcalendar callbacks to update events if dragged
 * Todo: add fc callbacks for times
 * Todo: add fc draggable to create event ?
 *
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

    /**
     * Prepare Calendar fullCalendar object with events and options
     *
     * @param $calendar_events
     * @return \Calendar
     */
    public function prepCalendar($calendar_events)
    {
        // TODO: Build Calendar class for options and docs
        $calendarOptions = [
            'header' => ['left' => 'prev,next today', 'center' => 'title',
                'right' => Auth::guard('admin')->check() ? 'month,agendaWeek,agendaDay' : 'agendaWeek,agendaDay' ],
            'defaultView' => 'agendaWeek',
            'allDaySlot' => false,
            'weekends' => false,
            'displayEventEnd' => false,
            'slotDuration' => '00:30:00',
            'minTime' => '08:00:00',
            'maxTime' => '18:00:00',
            'weekNumbers' => true,
            'navLinks' => true,
            'locale' => 'en-gb',
            'views' => ['agenda' => ['displayEventTime' => false]]
        ];

        $calendar = Calendar::addEvents($calendar_events)->setOptions($calendarOptions);
        return $calendar;
    }

    /**
     * Paginate Collection
     *
     * @param array|Collection      $items
     * @param int   $perPage
     * @param int  $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: Paginator::resolveCurrentPage();
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            array_key_exists('path', $options) ? $options : array_merge($options, [ 'path' => LengthAwarePaginator::resolveCurrentPath()]));
    }

    //------------------------------------------------------------------------------------------------------------------
    // Resource Controller section

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$calendar_events = CalendarEvent::all(); //Too Many?
        $calendar_events = CalendarEvent::where('user_id', '!=', '1')->whereBetween(
            'start', [Carbon::today(), Carbon::today()->addMonths(3)])->get();
        $today = Carbon::today()->toDateString();
        $calendar_events_sorted = CalendarEvent::whereDate('start', '=', $today)
            ->get()->sortby('start');

        $calendar = $this->prepCalendar($calendar_events);

        $admins = Admin::all()->pluck('name', 'id');

        \JavaScript::put(['admin' => Auth::guard('admin')->check()]);

        return view('calendar_events.index', compact('calendar_events', 'calendar_events_sorted', 'calendar', 'admins'));
    }

    /**
     * Utility method to save event
     *
     * @param Request $request
     * @param CalendarEvent $calendar_event
     */
    public function saveCalendarEvent(Request $request, CalendarEvent $calendar_event): void
    {
        $calendar_event->title          = $request->input("title");
        $calendar_event->start          = Carbon::parse($request->input("start"));
        $calendar_event->end            = Carbon::parse($request->input("start"))->addMinutes(30);
        $calendar_event->user_id        = Auth::user()->getAuthIdentifier();
        $calendar_event->admin_id       = $request->input('selectAdmin');

        $calendar_event->save();
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
        $admin_id = $request->input('selectAdmin');
        $admins = Admin::all()->pluck('name', 'id');
        $calendar_events = CalendarEvent::where('admin_id', $admin_id)->get()->sortby('start');
        $user_id = $request->filled('user_id') ? $request->input('user_id') : null;

        \JavaScript::put(['admin_id' => $admin_id]);

        $calendar = $this->prepCalendar($calendar_events);

        return view('calendar_events.create', compact('calendar', 'admin_id', 'admins', 'user_id'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\StoreCalendarEvent $request
     * @return Response
     */
    public function store(Requests\StoreCalendarEvent $request)
    {
        $calendar_event = new CalendarEvent();
        // Validation in type-hinted form request
        $this->saveCalendarEvent($request, $calendar_event);

        return redirect()->route('calendar_events.index')->with('success', 'Item created successfully.');
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
        $admin = $calendar_event->admin()->first();
        $admins = Admin::all()->pluck('name', 'id');
        $admin_id = $admin->id;
        $calendar_events = $admin->events()->get()->sortby('start');
        $calendar = $this->prepCalendar($calendar_events);

        \JavaScript::put(['event' => $calendar_event]);

        return view('calendar_events.edit', compact('calendar', 'calendar_event', 'admins', 'admin_id'));
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

        return redirect()->route('calendar_events.index')->with('warning', 'Item deleted successfully.');
    }

    //------------------------------------------------------------------------------------------------------------------
    // Return events for AJAX call

    public function eventsByAdmin(Admin $admin_id)
    {
        return $admin_id->events()->whereBetween('start', [Carbon::today(), Carbon::today()->addMonths(2)])->get();
    }

    public function eventsByUser(User $user_id)
    {
        return $user_id->events()->whereBetween('start', [Carbon::today(), Carbon::today()->addMonths(2)])->get();
    }

    //------------------------------------------------------------------------------------------------------------------
    // Paginated results - requires $this->>paginate() method above

    /**
     * Get paginated events for any User where name or email includes $request['name']
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eventsByUserName(Request $request)
    {
        $name = $request->input('searchNameValue');
        $uCname = ucwords($name);
        $firstname = preg_split('/\s+/', $uCname)[0];

        $results = collect();

        $users = User::where('name', '=', "$uCname")
        ->orWhere('name', 'like', "$firstname%")
        ->orWhere('email', '=', mb_strtolower($name))
        ->get();

        foreach ($users as $user) {
            $results = $results->merge($user->events()->get()->sortBy('start'));
        }

        $calendar_events = $calendar_events_sorted = $this->paginate(
            $results, 10, null,
            ['path' => route('calendar_events.events.userName', ['searchNameValue' => $name])]);

        $calendar = $this->prepCalendar($calendar_events);

        $admins = Admin::all()->pluck('name', 'id');

        return view('calendar_events.index', compact('calendar_events', 'calendar_events_sorted', 'calendar', 'admins'));
    }

    /**
     * Get paginated results for Admin
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eventsByAdminPost(Request $request)
    {
        $admin = Admin::find($request->input('selectAdmin'));

        $calendar_events = $results = $admin->events()->get()->sortby('start');
        $calendar_events_sorted = $this->paginate($results, 10, null, ['path' => route('calendar_events.events.admin', ['selectAdmin' => $admin->id])]);

        $calendar = $this->prepCalendar($calendar_events);

        $admins = Admin::all()->pluck('name', 'id');

        return view('calendar_events.index', compact('calendar_events', 'calendar_events_sorted', 'calendar', 'admins'));
    }

    //------------------------------------------------------------------------------------------------------------------
}
