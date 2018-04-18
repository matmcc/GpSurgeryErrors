@extends('layouts.app')

@section('content')
    <div class="col-sm-8">
        <h3>{{ $result->name }}</h3>
        <p class="small">{{ $result->date->toFormattedDateString() }}</p>

        <p>{{ $result->body }}</p>

        <a class="btn btn-outline-info" href="{{ url()->previous() }}">Back</a>
    </div>
@endsection