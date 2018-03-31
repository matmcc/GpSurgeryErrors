@if(count($errors))
    <div class="form-group">

        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    </div>
@endif

@if (session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif

@if (session('status'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif
