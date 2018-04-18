@extends('layouts.app')

@section('style')

@stop

@section('content')

    <div class="page-header">
        <h3>Prescriptions</h3>
    </div>
    <hr>

    <div class="card">
        <div class="card-header" id="presHeader">
            Your Prescriptions
        </div>
        <div class="card-body">
        @if(count($prescriptions) == 0)
            @component('layouts.empty')
                    <strong>You do not have any prescriptions.</strong>
                    <br>Although you can extend your prescriptions here after a Dr has initially prescribed them,
                    all prescription-only medicines must first be prescribed by a Dr.
            @endcomponent
        @else
            <div class="table-responsive-md">
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th>Prescription</th>
                        <th class="text-right">Options</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->name }}</td>

                        <td class="text-right">
                            @if($prescription->isRenewable)
                                <a href="" class="btn btn-outline-success">Renew</a>
                            @endif
                            <a href="{{ 'https://en.wikipedia.org/wiki/'.$prescription->name }}"
                               class="btn btn-outline-info"
                               target="_blank">Info</a>
                        </td>
                    </tr>

                @endforeach
                </tbody>

            </table>
            @endif
            </div>
        </div>
    </div>

@endsection

@section('script')

    @parent
@endsection
