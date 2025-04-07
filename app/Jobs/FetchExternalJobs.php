<?php

namespace App\Jobs;

use App\Models\ExternalJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExternalJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle()
    {
        try {
            $response = Http::get(config('services.external_jobs.url'));
            
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                
                foreach ($xml->position as $position) {
                    $externalId = (string)$position->id;
                    $title = (string)$position->name;
                    $description = (string)$position->jobDescriptions->jobDescription->value;
                    $link = (string)$position->jobAdvertisements->jobAdvertisement->url;
                    
                    ExternalJob::updateOrCreate(
                        ['external_id' => $externalId],
                        [
                            'title' => $title,
                            'description' => $description,
                            'link' => $link,
                        ]
                    );
                }
                
                Log::info('Successfully fetched external jobs');
            } else {
                Log::error('Failed to fetch external jobs: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching external jobs: ' . $e->getMessage());
        }
    }
}
