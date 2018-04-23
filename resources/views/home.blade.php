@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @guest
        <div class="card">
            <div class="card-header text-center">
                <h4>Welcome to Over Surgery</h4>
            </div>
            <div class="card-body">
                So, you're sick? Fear not, you've come to the right place - we can help you get over it.<br>
                We've got doctors, nurses, drugs and tests. You can book, you can contact us, we have drop-in surgery hours,
                a telephone number, and a chatty receptionist - all the modern conveniences of a truly up-to-date
                Dr's surgery. No trepanning, no homeopathy, no voodoo; just scientifically proven medical care,
                all via our snazzy new website
                <hr>
                <h5>How do I use it?</h5>
                Well, you have to <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> first. Then we can start to help you get better
            </div>
        </div>
        @endguest

        @auth
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
        @endauth
    </div>
</div>
@endsection
