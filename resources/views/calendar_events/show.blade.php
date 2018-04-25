@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h3>Your Appointment</h3>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="#">

                <hr>
                @if(Auth::guard('admin')->check())
                    <div class="form-group">
                         <label for="title"></label>
                         <p class="form-control-static">Appointment for: {{ $calendar_event->user()->first()->name }}</p>
                    </div>
                @endif
                <div class="form-group">
                     <label for="title"></label>
                     <p class="form-control-static">Appointment with: {{ ucfirst($calendar_event->admin()->first()->job_title) .' '. $calendar_event->admin()->first()->name }}</p>
                </div>
                    <div class="form-group">
                     <label for="start_time"></label>
                     <p class="form-control-static">Appointment begins:  {{$calendar_event->start->format('H:i \\o\\n \\t\\h\\e jS \\of F')}}</p>
                </div>
                    <div class="form-group">
                     <label for="end_time"></label>
                     <p class="form-control-static">Appointment ends at: {{$calendar_event->end->format('H:i')}}</p>
                </div>
                <br>
                <hr>

            </form>

            <div class="btn-group" role="group" aria-label="Calendar Event option buttons">
                <a class="btn btn-info d-none d-sm-block " href="{{ route('calendar_events.index') }}">Back</a>
                <a class="btn btn-info btn-sm d-block d-sm-none" href="{{ route('calendar_events.index') }}">Back</a>
                <a class="btn btn-warning d-none d-sm-block " href="{{ route('calendar_events.edit', $calendar_event->id) }}">Edit</a>
                <a class="btn btn-warning btn-sm d-block d-sm-none" href="{{ route('calendar_events.edit', $calendar_event->id) }}">Edit</a>
                <form action="#/$calendar_event->id" method="DELETE" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false }">
                    <button class="d-none d-sm-block btn btn-danger" type="submit">Delete</button>
                    <button class="d-block d-sm-none btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                </form>
            </div>

        </div>
    </div>


@endsection