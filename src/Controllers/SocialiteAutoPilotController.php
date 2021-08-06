<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ConfrariaWeb\YoutubeAutoPilot\Services\YoutubeAutoPilotService;

class SocialiteAutoPilotController extends Controller
{

    protected $youtubeAutoPilotService;

    function __construct(YoutubeAutoPilotService $youtubeAutoPilotService)
    {
        $this->youtubeAutoPilotService = $youtubeAutoPilotService;
    }

    public function redirect()
    {
        return $this->youtubeAutoPilotService->redirect();
    }

    public function callback(Request $request)
    {
        $channel = $this->youtubeAutoPilotService->callback($request);
        return redirect()->route('dashboard.youtube.channels.edit', $channel->id)->with('status', 'Channel updated!');
    }

}
