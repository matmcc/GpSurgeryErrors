@extends('layouts.master')

@section('content')

    <div class="col-sm-8 blog-main">
        <h3>Create a post</h3>
        <hr>

        <form method="POST" action="/posts">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="Title">Title</label>
                <input type="text" class="form-control" id="Title" name="title">
            </div>

            <div class="form-group">
                <label for="Body">Body</label>
                <textarea id="Body" name="body" class="form-control"></textarea>
            </div>

              <div class="form-group">
                  <button type="submit" class="btn btn-primary">Publish</button>
              </div>

            @include('layouts.errors')
        </form>

    </div>

@endsection