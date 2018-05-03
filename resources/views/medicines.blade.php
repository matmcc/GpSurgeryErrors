@extends('layouts.app')

@section('style')
@endsection

@section('content')
    <div class="container">

            <div class="card">

                <div class="card-header text-center">
                    <h4>Prescription Only Medications</h4>
                </div>

                <div class="card-body">
                    All prescription-only medicines must first be prescribed by a Dr.<br>
                    Once you have a prescription, you can renew prescriptions online.<br>
                    <br>
                    <h5>Available Prescriptions:</h5>
                    <!-- TODO -->
                    <ul class="list-group list-group-flush">
                        <div class="row">
                        @foreach($medicines->sortBy('name') as $med)
                            <li class="list-group-item col-sm-6 col-md-3">{{ $med->name }}</li>
                        @endforeach
                        </div>
                    </ul>
                </div>

            </div>

    </div>
@endsection
