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
    <div class="alert alert-primary">
        {{ session('message') }}
    </div>
@endif

@if (session('status'))
    <div class="alert alert-secondary">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-danger">
        {{ session('status') }}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        {{ session('message') }}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif
