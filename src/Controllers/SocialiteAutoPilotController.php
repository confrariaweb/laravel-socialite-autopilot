<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteAccount;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteMedia;
use ConfrariaWeb\SocialiteAutoPilot\Services\SocialiteAutoPilotService;

class SocialiteAutoPilotController extends Controller
{

    protected $socialiteAutoPilotService;

    function __construct(SocialiteAutoPilotService $socialiteAutoPilotService)
    {
        $this->socialiteAutoPilotService = $socialiteAutoPilotService;
    }

    public function redirect($provider)
    {
        return $this->socialiteAutoPilotService->redirect($provider);
    }

    public function callback($provider, Request $request)
    {
        $social = $this->socialiteAutoPilotService->callback($request, $provider);
        return redirect()->route('dashboard.accounts.index')->with('status', 'Provedor atualizado!');
    }

    public function accounts(){
        $data['accounts'] = SocialiteAccount::all();
        return view('socialiteAutoPilot::accounts', $data);
    }

    public function medias(){
        $data['medias'] = SocialiteMedia::all();
        return view('socialiteAutoPilot::medias', $data);
    }

    public function createMedias(){
        $data['accounts'] = SocialiteAccount::all();
        return view('socialiteAutoPilot::create_medias', $data);
    }

}
