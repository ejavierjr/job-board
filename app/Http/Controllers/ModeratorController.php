<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function approve(Job $job)
    {
        $job->update(['is_approved' => true]);
        return response()->json(['message' => 'Job approved successfully']);
    }
    
    public function markAsSpam(Job $job)
    {
        $job->update(['is_spam' => true]);
        return response()->json(['message' => 'Job marked as spam']);
    }
}
