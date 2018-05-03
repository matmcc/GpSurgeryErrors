@extends('layouts.app')
@php
$apiKey = 'AIzaSyC_bz97z_S5S0bDQzWlC25D3Tj_6few9IU'
@endphp
@section('style')
    <style>
        #map {
            width: 400px;
            height: 400px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

                <div class="card">
                    <div class="card-header text-center">
                        <h4>Contact Details</h4>
                    </div>
                    <div class="card-body">

                    <div class="row">
                            <div class="col-sm" id="contactDetails">
                                <strong>E-mail: </strong>reception@oversurgery.co.uk<br>
                                <strong>Telephone: </strong>01954 231550<br>
                                <strong>Address:</strong><br>
                                1 Drings Close<br>
                                Over<br>
                                Cambridge<br>
                                Cambridgeshire<br>
                                CB24 5NZ<br>
                                <strong>Opening Hours:</strong><br>
                                Monday - Friday: 08:00 - 18:00<br>
                                Saturday - Sunday: ( Closed )<br>
                            </div>

                            <div class="col-sm" id="map">
                                <script>
                                    function initMap() {
                                        var overSurgery = {lat: 52.317, lng: 0.012};
                                        var map = new google.maps.Map(document.getElementById('map'), {
                                            zoom: 14,
                                            center: overSurgery
                                        });
                                        var marker = new google.maps.Marker({
                                            position: overSurgery,
                                            map: map
                                        });
                                    }
                                </script>
                                <script async defer
                                        src={{ "https://maps.googleapis.com/maps/api/js?key=$apiKey&callback=initMap"}}>
                                </script>
                            </div>
                        </div>

                    </div>
                </div>

        </div>
    </div>
@endsection
