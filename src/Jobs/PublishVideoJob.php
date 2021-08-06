<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Jobs;

use ConfrariaWeb\YoutubeAutoPilot\Models\YoutubeVideo;
use ConfrariaWeb\YoutubeAutoPilot\Services\YoutubeAutoPilotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(YoutubeVideo $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(YoutubeAutoPilotService $youtubeService)
    {
        $response = $youtubeService->publishVideo($this->video);
    }
}
