<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Jobs;

use ConfrariaWeb\YoutubeAutoPilot\Models\YoutubeSearch;
use ConfrariaWeb\YoutubeAutoPilot\Services\YoutubeAutoPilotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchVideos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $search;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(YoutubeSearch $search)
    {
        $this->search = $search;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(YoutubeAutoPilotService $youtubeService)
    {
          $response = $youtubeService->searchChannelVideos($this->search);
          return $response;
    }
}
