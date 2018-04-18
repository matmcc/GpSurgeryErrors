<div class="col-md-4">
    <div class="card mb-4 box-shadow">
        <div class="card-header">
            {{ $title }}
        </div>
        <div class="card-body">
            <p class="card-text">{{ $body }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a class="btn btn-sm btn-outline-secondary"
                       href="{{ $view }}">View</a>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">{{ $date }}</small>
            </div>
        </div>
    </div>
</div>