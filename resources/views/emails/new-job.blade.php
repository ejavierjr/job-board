@component('mail::message')
# New Job Submission Requires Moderation

**Title:** {{ $job->title }}  
**Submitted by:** {{ $job->email }}  

@component('mail::panel')
{{ Str::limit($job->description, 200) }}
@endcomponent

@component('mail::button', ['url' => route('moderator.pending')])
Review Submission
@endcomponent

@endcomponent