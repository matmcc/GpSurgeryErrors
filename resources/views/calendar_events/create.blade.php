@extends('layouts.app')


@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css" />
@stop


@section('content')
    <div class="page-header">
        @if(Auth::guard('admin')->check())
            <h3>Book Appointment for {{ App\User::find($user_id)->name  }}</h3>
        @else
            <h3>Book Appointment with {{ $admins[$admin_id] }}</h3>
        @endif
    </div>
    <hr>

    <div class="">
        <div class="col-md-12">

            <div class="form-group">
                @include('layouts.errors')
            </div>

            <form action="{{ route('calendar_events.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="selectAdmin" value="{{ $admin_id }}">
                @if(Auth::guard('admin')->check())
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                @endif

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
                            <input class="form-control" id="timePicker" type="text" class="time" required/>
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
                        ['name' => 'selectAdmin', 'id' => 'selectAdmin_id', 'admin' => $admin_id, ])

                        @if ($errors->has('selectAdmin'))
                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('selectAdmin') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>


                <div class="row justify-content-end" style="padding-top: 20px">

                        <div class="col-4 col-sm-3 col-md-2">
                            <a class="btn btn-outline-info btn-block" href="{{ route('calendar_events.index') }}">Back</a>
                        </div>
                        <div class="col-8 col-sm-6 col-md-3">
                            <input class="btn btn-success btn-block" type="submit" value="Create"/>
                        </div>

                </div>

            </form>
        </div>
    <hr>
    </div>

    <br>

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
    @if(! empty($calendar))
        {!! $calendar->script() !!}
    @endif
    <!-- jQuery AJAX setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    <script type="text/javascript">

        $(function () {

            // Set up variables -----------------------------------------------------------------------
            var datePicker = $('#datepicker-start');
            var date = moment().startOf('day');
            //console.log('Date: ', date.toString());
            var time = '00:00';
            var admin_id = JsVar.admin_id;
            var disabledTimes = [];

            var calendar = $('.fc').fullCalendar('getCalendar');
            calendar.option('locale', 'en-gb');
            $('.fc').fullCalendar({events: 'events/' + admin_id});

            // Functions -----------------------------------------------------------------------

            function getDisabledTimesByAjax(date) {
                var dTimes = [];
                $.when(
                    $.getJSON('events/admin/' + admin_id, function (data) {
                        console.log(data);
                            $.each(data, function (i, event) {
                                var $start = moment(event.start, 'YYYY-MM-DD[T]HH:mm:ss', 'en-gb');
                                if($start.isSame(date, 'day')) {
                                    var $end = moment(event.end, 'YYYY-MM-DD[T]HH:mm:ss', 'en-gb');
                                    dTimes.push([$start.format('H:mm'), $end.format('H:mm')]);
                                }
                            })
                        })
                ).then( function() {
                    console.log('Disabled times: ', dTimes);
                    $('#timePicker').timepicker('remove');
                    $('#timePicker').timepicker({
                        'timeFormat': 'H:i',
                        'step': 30,
                        'forceRoundTime': true,
                        //'useSelect': true,
                        'minTime': '8am',
                        'maxTime': '5:30pm',
                        'disableTimeRanges': dTimes
                    });
                    disabledTimes = dTimes;
                });
            }
            // TODO: Change JSON request to include date

            function updateStartTime(date, time) {
                date.startOf('day');
                date.add(moment.duration(time));
                $('#start').val(date);
                console.log("updateStartTime: ", date.toString());
                return date;
            }

            // Additional setup for elements -----------------------------------------------------------------------

            // Set up datepicker options
            datePicker.datetimepicker({
                daysOfWeekDisabled: [0, 6],
                disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18 }), moment({ h: 24 })]],
                locale: 'en-gb',
                format: 'L'
            });

            getDisabledTimesByAjax(date);

            // Callbacks -----------------------------------------------------------------------

            $( "#loadAjax" ).hide();
            $( document ).ajaxStart(function() {
                $( "#calHeader" ).addClass('bg-info').addClass('text-center');
                $( "#calHeader" ).text('Loading Calendar Events');
            });
            $( document ).ajaxComplete(function() {
                $( "#calHeader" ).removeClass('bg-info').removeClass('text-center');
                $( "#calHeader" ).text('Calendar');
            });

            // on date change - update disabled times; update startDatetime
            datePicker.on("change.datetimepicker", function (e) {
                getDisabledTimesByAjax(e.date);
                date = e.date;
                calendar.gotoDate(e.date);
                updateStartTime(e.date, time);
            });

            // on FullCalendar date change - update dTimes, update start
            calendar.on('viewRender', function(){
                // getDate seemed to ignore locale, so using moment().local() to force this
                date = calendar.getDate().local();
                datePicker.datetimepicker('date', date);
                datePicker.datetimepicker('locale', 'en-gb');
                datePicker.datetimepicker('format', 'L');
            });

            // on time change - update startDatetime
            $('#timePicker').on('changeTime', function() {
                time = $(this).val();
                updateStartTime(date, time);
            });

            // $('#start').on('change', function() {
            //     console.log("StartTime changed: ", $(this).val());
            // });

            $('#selectAdmin_id').on('change', function() {
                admin_id = $(this).val();
                $('.fc').fullCalendar('removeEvents');
                $('.fc').fullCalendar('addEventSource', 'events/admin/' + admin_id);
                getDisabledTimesByAjax(date);
                console.log("Admin changed: ", admin_id);
            });

            $('#timePicker_postText').on('click', function () {
                $('#timePicker').timepicker('show');
            })

        });

    </script>
    @parent
@stop