@extends('layouts.app')


@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" />
@stop


@section('content')
    <div class="page-header">
        <h1>CalendarEvents / Create </h1>
    </div>


    <div class="row">
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

                    {{--<div class="col-md-10" style="overflow:hidden;">--}}
                        <div class="form-group">
                            <label for="datepicker-start">Start Date</label>
                            <div class="input-group date" id="datepicker-start" data-target-input="nearest">
                                {{--<input type="text" id="datepicker-start" class="form-control datetimepicker-input" data-target="#datepicker-start"/>--}}
                                {{--<div class="input-group-append" data-target="#datepicker-start" data-toggle="datetimepicker">--}}
                                    {{--<div class="input-group-text"><i class="fa fa-calendar"></i></div>--}}
                            </div>

                            <input type="hidden" name="start" id="start" value=""/>
                        </div>
                    {{--</div>--}}


                    {{--<div class="col-md-6">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="timepicker-start">Start Time</label>--}}
                            {{--<div class="input-group date" id="timepicker-start" data-target-input="nearest">--}}
                                {{--<input type="text" id="timepicker-start" class="form-control datetimepicker-input" data-target="#timepicker-start"/>--}}
                                {{--<div class="input-group-append" data-target="#timepicker-start" data-toggle="datetimepicker">--}}
                                    {{--<div class="input-group-text"><i class="fa fa-clock-o"></i></div>--}}
                                {{--</div>--}}
                                {{--<input type="hidden" name="start_time" id="start_time" value=""/>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                <div class="form-group">
                    <label for="selectAdmin_id">{{ __('Dr:') }}</label>
                    <div class="">
                        {{--{!! Form::select('dr', $admins, null) !!}--}}
                        @include('layouts.selectBookable', ['name' => 'selectAdmin', 'id' => 'selectAdmin_id'])

                        @if ($errors->has('selectAdmin'))
                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('selectAdmin') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>

                    <div class="form-group">
                     <label for="is_all_day">IS_ALL_DAY</label>
                     <input type="text" id="is_all_day" name="is_all_day" class="form-control" value=""/>
                </div>
                    <div class="form-group">
                     <label for="background_color">BACKGROUND_COLOR</label>
                     <input type="text" id="background_color" name="background_color" class="form-control" value=""/>
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
    <script type="text/javascript">

        $(function () {

            $('#datepicker-start').datetimepicker({
                daysOfWeekDisabled: [0, 6],
                disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18 }), moment({ h: 24 })]],
                inline: true,
                sideBySide: true
                //format: 'L'
            });

            $('#datepicker-start').on("change.datetimepicker", function (e) {
                console.log(e.date);
                $('#start').val(e.date);
            });

            $('#selectAdmin_id').on('change', function() {
            var data = {
                'admin_id': $(this).val()
            };
            console.log(data);
            {{--$.post('{{ route("ajax.user_select") }}', data, function(data, textStatus, xhr) {--}}
                {{--/*optional stuff to do after success */--}}
                {{--console.log(data);--}}
                {{--$('#username').html(data.username);--}}
                {{--$('#email').html(data.email);--}}
            {{--});--}}
        });
        });




        // $(function () {
        //     $('#timepicker-start').datetimepicker({
        //         daysOfWeekDisabled: [0, 6],
        //         disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18 }), moment({ h: 24 })]],
        //         format: 'LT'
        //
        //     });
        //     $('#timepicker-start').on("change.datetimepicker", function (e) {
        //         //console.log(e.date);
        //         $('#start_time').val(e.date);
        //     });
        // });

    </script>
    @parent
@stop