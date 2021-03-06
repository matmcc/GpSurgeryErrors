@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h3>Appointments</h3>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if(Auth::guard('admin')->check())
                <!-- Admin section -->
                <form class="form"
                      id="searchName"
                      action="{{ route('calendar_events.events.userName') }}"
                      method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="searchName_preText">Search for user:</span>
                        </div>
                            <input class="form-control"
                                   name="searchNameValue"
                                   id="searchNameField"
                                   type="text"
                                   placeholder="Search by name or email"
                                   required
                                   aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('calendar_events.events.admin') }}" method="GET">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @include('layouts.selectBookable',
                                ['name' => 'selectAdmin',
                                'id' => 'selectAdmin_id',
                                'prepend' => 'Events by admin:',
                                'button' => 'Search'])
                            </div>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </form>

                <table class="table table-striped">

                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Staff</th>
                        <th>User</th>
                        <th class="text-right">Options</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($calendar_events_sorted as $event)
                        <tr>
                            <td>{{$event->start->format('jS \\of F \\a\\t H:i')}}</td>
                            <td>{{$event->admin()->first()->name}}</td>
                            <td>{{$event->user()->first()->name}}</td>

                            <td class="text-right">
                                <div class="btn-group" role="group" aria-label="User option buttons">
                                    <form action="{{ route('calendar_events.create.admin',
                                                    ['admin_id' => $event->admin()->first()->id,
                                                    'user_id' => $event->user()->first()->id]) }}"
                                          method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="selectAdmin" value="{{ $event->admin()->first()->id }}">
                                        <input type="hidden" name="user_id" value="{{  $event->user()->first()->id }}">
                                        <button class="d-none d-sm-block btn btn-outline-success btn-sm" type="submit">Book</button>
                                        <button class="d-block d-sm-none btn btn-outline-danger btn-sm" type="submit"><i class="fa fa-plus"></i></button>
                                    </form>
                                    <a class="btn btn-secondary btn-sm" href="{{ route('prescriptions.user', $event->user()->first()) }}">Pres.</a>
                                    <a class="btn btn-dark btn-sm" href="{{ route('results.user', $event->user()->first()) }}">Resu.</a>
                                </div>
                                <div class="btn-group" role="group" aria-label="Calendar Event option buttons">
                                    <a class="btn btn-outline-info btn-sm" href="{{ route('calendar_events.show', $event->id) }}">View</a>
                                    <a class="btn btn-outline-warning btn-sm" href="{{ route('calendar_events.edit', $event->id) }}">Edit</a>
                                    <form action="{{ route('calendar_events.destroy', $event->id) }}"
                                          method="POST"
                                          onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="d-none d-sm-block btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                        <button class="d-block d-sm-none btn btn-outline-danger btn-sm" type="submit"><i class="fa fa-trash" style="color: darkred"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

                @if($calendar_events_sorted instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $calendar_events_sorted->links() }}
                @endif

                <!-- End Admin section -->


            @elseif(Auth::guard('web')->check())
                <!-- User section -->
            @if(count($calendar_events) == 0 ?? !isset($calendar_events))
                @component('layouts.empty')
                    <strong>You do not have any appointments.</strong>
                    <br>You can use the select box below to choose a Dr or Nurse with whom to book an apppointment.
                    <br>The calendar below shows the general availability of appointments.
                @endcomponent
            @else
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th>Appointment</th>
                        <th>Staff</th>
                        <th class="text-right">Options</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($calendar_events->sortBy('start') as $calendar_event)

                    @if($calendar_event->user_id == Auth::user()->id)
                    <tr>
                        {{--style="background-color: {{ $calendar_event->admin()->first()->color }}; filter: brightness(150%)"--}}
                        <td>{{$calendar_event->start->format('jS \\of F \\a\\t H:i')}}</td>
                        <td>{{$admins[$calendar_event->admin_id]}}</td>

                        <td class="text-right">
                            <div class="btn-group" role="group" aria-label="Calendar Event option buttons">
                                <a class="btn btn-outline-info btn-sm" href="{{ route('calendar_events.show', $calendar_event->id) }}">View</a>
                                <a class="btn btn-outline-warning btn-sm" href="{{ route('calendar_events.edit', $calendar_event->id) }}">Edit</a>
                                <form action="{{ route('calendar_events.destroy', $calendar_event->id) }}"
                                      method="POST"
                                      onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="d-none d-sm-block btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                    <button class="d-block d-sm-none btn btn-outline-danger btn-sm" type="submit"><i class="fa fa-trash" style="color: darkred"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endif

                @endforeach
                </tbody>

            </table>
            @endif

            <form action="{{ route('calendar_events.create.admin') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            @include('layouts.selectBookable',
                            ['name' => 'selectAdmin',
                            'id' => 'selectAdmin_id',
                            'button' => 'Book Appointment'])
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
            </form>
            @endif
            <!-- End User section -->

        </div>
    </div>
    <hr>

    <div class="card">
        <div class="card-header" id="calHeader">
            Calendar
        </div>
        <div class="card-body">
            @if(! empty($calendar))
                {!! $calendar->calendar() !!}
            @endif
        </div>
    </div>

@endsection

@section('script')
    {!! $calendar->script() !!}
    <!-- jQuery AJAX setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(function () {
            var admin = JsVar.admin;
            var calendar = $('.fc').fullCalendar('getCalendar');

            // FullCalendar updates...
            // remove month view if not admin
            if (!admin) {
                console.log('set options', admin);
                $('.fc').fullCalendar('option', 'header', {'left': 'prev,next today', 'center': 'title', 'right': 'agendaWeek,agendaDay'});
            }
            // remove today button if screen width < 600px
            if ($(window).width() < 600) {
                $('.fc').fullCalendar('option', 'header', {'left': 'prev,next', 'center': 'title', 'right': 'agendaWeek,agendaDay'});
            }

            $( document ).ajaxStart(function() {
                $( "#calHeader" ).addClass('bg-info').addClass('text-center');
                $( "#calHeader" ).text('Loading Calendar Events');
            });
            $( document ).ajaxComplete(function() {
                $( "#calHeader" ).removeClass('bg-info').removeClass('text-center');
                $( "#calHeader" ).text('Calendar');
            });

            $('#selectAdmin_id').on('change', function() {
                admin = $(this).val();
                calendar.removeEvents();
                calendar.addEventSource('calendar_events/events/admin/' + admin);
                //console.log("Admin changed: ", admin);
            });

            calendar.on('viewRender', function(view, element){
                if( $('#selectAdmin_id').val()) {
                    calendar.removeEvents();
                    calendar.addEventSource('calendar_events/events/admin/' + admin);
                }
            });

        })
    </script>
@endsection