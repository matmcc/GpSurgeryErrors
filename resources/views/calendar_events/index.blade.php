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
                            <span class="input-group-text" id="searchName_preText">Events by user:</span>
                        </div>
                            <input class="form-control"
                                   name="searchNameValue"
                                   id="searchNameField"
                                   type="text"
                                   placeholder="Search by name or email"
                                   aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </div>
                    </div>
                </form>

                <form action="{{ route('calendar_events.events.admin') }}" method="POST">
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

                <!-- End Admin section -->
            @elseif(Auth::guard('web')->check())
                <!-- User section -->
            @if(count($calendar_events) == 0)
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

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <hr>
                <div class="panel-heading" style="padding-top: 20px; padding-bottom: 10px">
                    <h4>Availability:</h4>
                </div>

                <div class="panel-body">
                    {!! $calendar->calendar() !!}
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    {!! $calendar->script() !!}

    <script>
        // $(".dropdown-toggle").dropdown();

        // var $tableRows;
        // $("#searchName").bind('submit', function (e) {
        //     console.log($("#searchNameField").val());
        //     $.getJSON('calendar_events/events/byname/' + $("#searchNameField").val(), function(data) {
        //         $.each(data, function(i, e) {
        //             $tableRows += "<tr>";
        //             $tableRows += "<td>";
        //
        //             $tableRows += "</td>";
        //             $tableRows += "</tr>";
        //         })
        //     });
        //     e.preventDefault();
        //     return false;
        // });
    </script>
@endsection