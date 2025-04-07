<?php

namespace App\Http\Controllers;

use App\Jobs\FetchExternalJobs;
use App\Models\Job;
use App\Models\ExternalJob;
use App\Notifications\NewJobNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $includeExternal = $request->boolean('includeExternal', false);
        
        $jobs = Job::approved()
            ->notSpam()
            ->local()
            ->latest()
            ->get();
            
        if ($includeExternal) {
            // Dispatch job to fetch external jobs if not recent
            if (ExternalJob::count() === 0 || 
                ExternalJob::latest()->first()->created_at->lt(now()->subHours(1))) {
                FetchExternalJobs::dispatch();
            }
            
            $externalJobs = ExternalJob::latest()->get()
                ->map(function ($job) {
                    return [
                        'title' => $job->title,
                        'description' => $job->description,
                        'external_link' => $job->link,
                        'is_external' => true,
                        'created_at' => $job->created_at,
                    ];
                });
                
            $jobs = $jobs->concat($externalJobs)
                ->sortByDesc('created_at')
                ->values();
        }
        
        return response()->json($jobs);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
        ]);
        
        $job = Job::create($validated);
        
        // Check if this is the first job from this email
        $isFirstJob = Job::where('email', $validated['email'])->count() === 1;
        
        if ($isFirstJob) {
            Notification::route('mail', config('app.moderator_email'))
                ->notify(new NewJobNotification($job));
        }
        
        return response()->json($job, 201);
    }
}
