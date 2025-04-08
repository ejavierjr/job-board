@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Jobs Requiring Moderation</h1>
    
    <div class="list-group">
        @forelse ($jobs as $job)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5>{{ $job->title }}</h5>
                        <p>{{ $job->description }}</p>
                        <small>Submitted by: {{ $job->email }}</small>
                    </div>
                    <div class="btn-group ms-3">
                        <form action="{{ route('moderator.approve', $job) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form action="{{ route('moderator.spam', $job) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Mark as Spam</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No jobs requiring moderation!</div>
        @endforelse
    </div>
</div>
@endsection