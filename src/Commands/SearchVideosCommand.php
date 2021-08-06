<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Commands;

use ConfrariaWeb\YoutubeAutoPilot\Jobs\SearchVideos as JobsSearchVideos;
use ConfrariaWeb\YoutubeAutoPilot\Services\YoutubeAutoPilotService;
use Illuminate\Console\Command;

class SearchVideosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search Videos';

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
        $allSearch = $youtubeAutoPilotService->allSearch();
        $allSearch->each(function ($item, $key) {
            JobsSearchVideos::dispatch($item);
        });
    }
}
