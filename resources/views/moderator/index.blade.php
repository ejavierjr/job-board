@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Jobs Needing Moderation</h1>
    
    <div class="list-group">
        @foreach($jobs as $job)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>{{ $job->title }}</h5>
                        <p class="mb-1">{{ Str::limit($job->description, 200) }}</p>
                        <small>Submitted by: {{ $job->email }}</small>
                    </div>
                    <div class="btn-group">
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
        @endforeach
    </div>
@endsection