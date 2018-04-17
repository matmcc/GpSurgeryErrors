@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h3>Appointments</h3>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if(Auth::guard('admin')->check())
                <!-- Admin section -->
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
                            <td>{{$event->start->format('H:i')}}</td>
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
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
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
            <table class="table table-striped">

                <thead>
                    <tr>
                        {{--<th>Title</th>--}}
                        {{--<th>Start</th>--}}
                        {{--<th>End</th>--}}
                        {{--<th>Staff</th>--}}
                        {{--<th>Colour</th>--}}
                        <th>Appointment</th>
                        <th>Staff</th>
                        <th class="text-right">Options</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($calendar_events->sortBy('start') as $calendar_event)

                    @if($calendar_event->user_id == Auth::user()->id)
                    <tr>
                        {{--<td>{{$calendar_event->title}}</td>--}}
                        {{--<td>{{$calendar_event->start->format('jS \\of F \\a\\t H:i')}}</td>--}}
                        {{--<td>{{$calendar_event->end}}</td>--}}
                        {{--<td>{{$calendar_event->admin_id}}</td>--}}
                        {{--<td>{{$calendar_event->background_color}}</td>--}}
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
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endif

                @endforeach
                </tbody>

            </table>

            <form action="{{ route('calendar_events.create.admin') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            @include('layouts.selectBookable',
                            ['name' => 'selectAdmin',
                            'id' => 'selectAdmin_id',
                            'button' => '<button class="btn btn-success" type="submit">Book Appointment</button>'])
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
@endsection