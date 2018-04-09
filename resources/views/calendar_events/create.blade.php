@extends('layouts.app')


@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css" />
@stop


@section('content')
    <div class="page-header">
        <h1>CalendarEvents / Create </h1>
    </div>


    <div class="">
        <div class="col-md-12">

            <div class="form-group">
                @include('layouts.errors')
            </div>

            <form action="{{ route('calendar_events.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                     <label for="title">Title</label>
                     <input type="text" id="'title" name="title" class="form-control" value=""/>
                </div>


                <div class="form-group">
                    <div class="input-group date" id="datepicker-start" data-target-input="nearest">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="datepicker-start_preText">Date:</span>
                        </div>
                        <input type="text" id="datepicker-start" class="form-control datetimepicker-input" data-target="#datepicker-start"/>
                        <div class="input-group-append" data-target="#datepicker-start" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    </div>

                    <input type="hidden" name="start" id="start" value=""/>
                </div>


                <div class="form group">
                    <div class="timePicker_disabledTimes">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="timePicker_preText">Time:</span>
                            </div>
                            <input class="form-control" id="timePicker" type="text" class="time"/>
                            <div class="input-group-append" id="timePicker_postText">
                                <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="selectAdmin_id"></label>
                    <div class="">
                        @include('layouts.selectBookable',
                        ['name' => 'selectAdmin', 'id' => 'selectAdmin_id', 'admin' => $admin_id])

                        @if ($errors->has('selectAdmin'))
                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('selectAdmin') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>


                    <a class="btn btn-default" href="{{ route('calendar_events.index') }}">Back</a>
                    <button class="btn btn-primary" type="submit" >Create</button>

            </form>
        </div>

    </div>

    <br>

    <div class="card">
        <div class="card-header">
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
    @if(! empty($calendar))
        {!! $calendar->script() !!}
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    <script type="text/javascript">

        $(function () {

            var calendar = $('.fc').fullCalendar('getCalendar');
            calendar.option('locale', 'en-gb');
            var datePicker = $('#datepicker-start');
            var date = datePicker.datetimepicker('viewDate').startOf('day');
            console.log('Date: ', date.toString());
            var time = '00:00';

            console.log(JsVar.disabledTimes[0]);

            function getDisabledTimes (date) {
                var dTimes = [];

                JsVar.disabledTimes[0].forEach(function (e) {
                    //console.log('dTimes_ date: ', date.startOf('day').toString(), 'also: ', moment(e[0]).toString());
                    if (moment(e[0]).isSame(date.startOf('day'))) {
                        dTimes.push(e[1]);
                        //console.log((e[1]));
                        //console.log("a Match!")
                    }
                });
                //console.log('Disabled times: ', dTimes);
                return dTimes;
            }

            function updateStartTime(date, time) {
                //time = typeof time !== 'undefined' ? time : '00:00';
                //console.log(time, date.toString());
                // TODO: next line needed?
                date.startOf('day');
                date.add(moment.duration(time));
                //console.log(date.toString());
                $('#start').val(date);
                console.log("updateStartTime: ", date.toString());
                return date;
            }

            // timepicker seems to mangle times passed into disableTimeRanges
            function updateTimePicker (disabledTimes) {
                $('#timePicker').timepicker({
                    'timeFormat': 'H:i',
                    'step': 30,
                    'forceRoundTime': true,
                    //'useSelect': true,
                    'minTime': '8am',
                    'maxTime': '5:30pm',
                    'disableTimeRanges': disabledTimes
                });
            }

            // Set up datepicker options
            datePicker.datetimepicker({
                daysOfWeekDisabled: [0, 6],
                disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18 }), moment({ h: 24 })]],
                locale: 'en-gb',
                format: 'L'
            });

            // Init timepicker
            var dTimes = getDisabledTimes(date);
            updateTimePicker(dTimes);

            // on date change - update disabled times; update startDatetime
            datePicker.on("change.datetimepicker", function (e) {
                //console.log("Date changed: ", e.date.format('L'));
                updateTimePicker(getDisabledTimes(e.date));
                date = e.date;
                calendar.gotoDate(e.date);
                updateStartTime(e.date, time);
            });

            // on FullCalendar date change - update dTimes, update start
            calendar.on('viewRender', function(view, element){
                // getDate seemed to ignore locale, so using moment().local() to force this
                date = calendar.getDate().local();
                //console.log('DATE: ', date.toString());
                datePicker.datetimepicker('date', date);
                datePicker.datetimepicker('format', 'L');
                // below updated by timepicker TODO: refactor into function
                //updateTimePicker(getDisabledTimes(date));
                //updateStartTime(date, time);
                //console.log('fc: ', date.toString());
            });

            // on time change - update startDatetime
            $('#timePicker').on('changeTime', function() {
                time = $(this).val();
                //console.log("Time changed: ", time);
                updateStartTime(date, time);
            });

            $('#start').on('change', function() {
                console.log("StartTime changed: ", $(this).val());
            });

            $('#selectAdmin_id').on('change', function() {
                var data = {
                    'admin_id': $(this).val()
                };
            console.log("Admin changed: ", data);
            });

        });

    </script>
    @parent
@stop