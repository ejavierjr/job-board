<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function index()
    {
        $jobs = Job::where('is_approved', false)
            ->where('is_spam', false)
            ->get();

        return view('moderator.index', compact('jobs'));
    }

    public function showPendingJobs()
    {
        return view('moderator.pending', [
            'jobs' => Job::where('is_approved', false)
                ->where('is_spam', false)
                ->latest()
                ->get()
        ]);
    }

    public function approveJob(Job $job)
    {
        $job->update(['is_approved' => true]);
        return back()->with('status', 'Job approved!');
    }

    public function markAsSpam(Job $job)
    {
        $job->update(['is_spam' => true]);
        return back()->with('status', 'Job marked as spam!');
    }
}
