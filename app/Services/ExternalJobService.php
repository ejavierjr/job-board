<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalJobService
{
    public function fetchJobs()
    {
        return Http::get(config('services.external_jobs.url'))
            ->throw()
            ->body();
    }

    public function parseJobs($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        $jobs = [];

        foreach ($xml->position as $position) {
            $description = '';
            
            // Concatenate all job description values
            foreach ($position->jobDescriptions->jobDescription as $jd) {
                $description .= strip_tags((string)$jd->value) . "\n\n";
            }

            $jobs[] = [
                'id' => (string)$position->id,
                'title' => (string)$position->name,
                'description' => trim($description),
                'external_link' => 'https://mrge-group-gmbh.jobs.personio.de/job/' . (string)$position->id,
                'location' => (string)$position->office,
                'employment_type' => (string)$position->employmentType,
                'created_at' => now()
            ];
        }

        return $jobs;
    }
}