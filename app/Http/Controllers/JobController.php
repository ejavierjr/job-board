<?php

namespace App\Http\Controllers;

use App\Jobs\FetchExternalJobs;
use App\Models\Job;
use App\Models\ExternalJob;
use App\Notifications\NewJobNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\ExternalJobService;

class JobController extends Controller
{

    // Controller example
    public function index()
    {
        $localJobs = Job::where('is_approved', true)
            ->where('is_spam', false)
            ->get()
            ->map(function ($job) {
                return $job->toArray() + ['is_external' => false];
            });

        $externalJobs = Cache::remember('external-jobs', 3600, function () {
            $service = new \App\Services\ExternalJobService();
            return $service->parseJobs($service->fetchJobs());
        });

        return view('jobs.index', [
            'jobs' => $localJobs->concat($externalJobs)->sortByDesc('created_at')
        ]);
    }


    public function showCreateForm()
    {
        return view('jobs.create');
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'email' => 'required|email',
            'location' => 'required|string',      // Add validation
            'employment_type' => 'required|in:full-time,part-time,contract,remote' // Validate allowed values
        ]);

        $job = Job::create($validated);

        if (Job::where('email', $validated['email'])->count() === 1) {
            Notification::route('mail', config('app.moderator_email'))
                ->notify(new NewJobNotification($job));
        }

        return redirect()->route('jobs.index')
            ->with('success', 'Job submitted for approval!');
    }
}
