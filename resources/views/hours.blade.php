@extends('layouts.app')

@section('style')
@endsection

@section('content')
    <div class="page-header">
        <h3>Staff Working Hours</h3>
    </div>
    <div class="container">
        <div class="row">

            @foreach($admin->sortBy('name') as $staff)
            <div class="card col-sm-6 col-lg-4">

                <div class="card-header text-center">
                    <h5>{{ ucwords($staff->job_title).' '.ucwords($staff->name) }} </h5>
                </div>

                <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Hours</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $i => $day)

                                <tr>
                                    <td>{{ $day }}</td>
                                    <td> {{ $staff->daysOff()->get()->pluck('day')->contains($i+1) ? '( Day Off )' : '08:00 - 18:00' }} </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
            @endforeach

        </div>
    </div>
@endsection
