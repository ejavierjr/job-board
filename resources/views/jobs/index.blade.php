@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Latest Job Opportunities</h1>
    
    <a href="{{ route('jobs.create') }}" class="btn btn-primary mb-4">
        Post New Job
    </a>

    <div class="row">
        @foreach ($jobs as $job)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $job['title'] }}</h5>
                        
                        @if(isset($job['location']) || isset($job['employment_type']))
                            <div class="mb-2 text-muted small">
                                @isset($job['location'])
                                    <span class="me-3"><i class="bi bi-geo-alt"></i> {{ $job['location'] }}</span>
                                @endisset
                                @isset($job['employment_type'])
                                    <span class="me-3"><i class="bi bi-clock"></i> {{ $job['employment_type'] }}</span>
                                @endisset
                            </div>
                        @endif

                        <p class="card-text">
                            {{ Str::limit(strip_tags($job['description']), 300) }}
                            @if(strlen(strip_tags($job['description'])) > 300)
                                [...] <a href="#" data-bs-toggle="modal" data-bs-target="#descModal{{ $loop->index }}">Read more</a>
                            @endif
                        </p>

                        @if(isset($job['external_link']))
                            <a href="{{ $job['external_link'] }}" 
                               class="btn btn-outline-primary"
                               target="_blank">
                                View External Posting
                            </a>
                        @else
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    Posted {{ \Carbon\Carbon::parse($job['created_at'])->diffForHumans() }}
                                </small>
                                <span class="badge bg-{{ $job['is_approved'] ? 'success' : 'warning' }}">
                                    {{ $job['is_approved'] ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description Modal -->
            <div class="modal fade" id="descModal{{ $loop->index }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $job['title'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            {!! nl2br(e($job['description'])) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection