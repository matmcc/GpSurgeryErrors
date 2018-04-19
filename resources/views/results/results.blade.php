@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h3>Results</h3>
    </div>
    <hr>

    @if(count($results) == 0)
    @component('layouts.empty')
        <strong>You do not have any test results.</strong>
        <br>If you believe you should have results, please get in touch.
    @endcomponent
@else
    <div class="row">
   @foreach($results->sortByDesc('date') as $result)

       @component('results.resultCard')
            @slot('title')
                {{ $result->name }}
            @endslot

            @slot('body')
                {{ $result->body }}
            @endslot

            @slot('view')
                {{ route('results.show', ['result' => $result->id]) }}
            @endslot

            @slot('date')
                {{ $result->date->toFormattedDateString() }}
            @endslot
        @endcomponent

   @endforeach
    </div>
@endif
@endsection
