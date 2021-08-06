<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Commands;

use ConfrariaWeb\YoutubeAutoPilot\Services\YoutubeAutoPilotService;
use Illuminate\Console\Command;

class PublishVideoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:video-publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Video';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(YoutubeAutoPilotService $youtubeAutoPilotService)
    {
        $youtubeAutoPilotService->publishVideos();
    }
}
