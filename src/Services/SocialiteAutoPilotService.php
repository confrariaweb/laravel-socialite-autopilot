<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Services;

use Auth;
use ConfrariaWeb\SocialiteAutoPilot\Jobs\DownloadVideo;
use ConfrariaWeb\SocialiteAutoPilot\Jobs\PublishVideoJob;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAutoPilotService
{

    function __construct()
    {
        
    }

    public function redirect($provider)
    {
        $scopes = Config::get('services.{$provider}.scopes');
        $social = Socialite::driver($provider);
        if($scopes){
            $social->scopes($scopes);
        }
        return $social->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $user = Socialite::driver($provider)->user();
        // OAuth 2.0 providers...
        //$token = $user->token;
        //$refreshToken = $user->refreshToken;
        //$expiresIn = $user->expiresIn;

        // OAuth 1.0 providers...
        //$token = $user->token;
        //$tokenSecret = $user->tokenSecret;

        // All providers...
        //$user->getId();
        //$user->getNickname();
        //$user->getName();
        //$user->getEmail();
        //$user->getAvatar();

        return SocialiteAccount::updateOrCreate(
            ['social_id' => $user->getId()],
            ['social_id' => $user->getId(), 'user_id' => Auth::id(), 'name' => $user->getNickname(), 'data' => $user, 'provider' => $provider]
        );
    }

    /*
    public function publishVideos($data = []){
        $videos = SocialiteVideo::whereNull('published_at')->whereNotNull('downloaded_at')->get();
        foreach($videos as $video){
            if (Storage::missing($video->path)) {
                continue;
            }
            PublishVideoJob::dispatch($video);
        }
    }

    public function publishVideo(SocialiteVideo $video){
        if (Storage::missing($video->path)) {
            return false;
        }
        $path = Storage::path($video->path);
        $uuu = new SocialiteService();
        $uuu->upload($video->channel->access_token, ['video_path' => $path]);
        return true;
    }

    public function updateOrCreateChannel($data)
    {
        return SocialiteChannel::updateOrCreate(['channel_id' => $data['channel_id']], $data);
    }

    public function findChannel($id){
        return SocialiteChannel::find($id);
    }

    public function allSearch(){
        return SocialiteSearch::all();
    }

    public function searchChannelVideos(SocialiteSearch $search, $amount = 5)
    {
        try{
            $channel = $this->findChannel($search->youtube_channel_id);
            $channelVideos = $this->youtubeService->listChannelVideos($search->channel_id, $amount);
            $channelVideos->each(function ($item, $key) use($channel){
                $data['youtube_channel_id'] = $channel->id;
                $data['video_id'] = $item->id;
                $data['title'] = $item->snippet->title;
                $data['description'] = $item->snippet->description;
                $data['tags'] = $item->snippet->tags;
                $video = $channel->videos()->updateOrCreate(
                    ['youtube_channel_id' => $channel->id, 'video_id' => $item->id],
                    $data
                );
                DownloadVideo::dispatch($video);
            });
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    public function downloadVideo($videoLink = null)
    {
        //
    }

    function downloadUrlToFile($url, $outFileName)
    {
        if (is_file($url)) {
            copy($url, $outFileName);
        } else {
            $options = array(
                CURLOPT_FILE    => fopen($outFileName, 'w'),
                CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
                CURLOPT_URL     => $url
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            curl_exec($ch);
            curl_close($ch);
        }
    }













    public function listChannels()
    {
        return  SocialiteChannel::all();
    }

    /*public function callback()
    {
        //$user = Socialite::driver('youtube')->user();
        $data = $this->prepareChannelData($user);
        $data = $this->auth($data);
        if (!$data) {
            return false;
        }
        return $this->updateOrCreate($data);
    }

    public function auth($data)
    {
        if (!Auth::check()) {
            return false;
        }
        $data['user_id'] = Auth::id();
        return $data;
    }

    public function find($id)
    {
        return SocialiteChannel::find($id);
    }

    public function updateOrCreate($data)
    {
        return SocialiteChannel::updateOrCreate(['code' => $data['code']], $data);
    }

    public function prepareChannelData($data)
    {
        return [
            'code' => $data->id,
            'access_token' => $data->accessTokenResponseBody,
            'token' => $data->token,
            'nickname' => $data->nickname,
            'avatar' => $data->avatar,
            'name' => $data->name ?? $data->nickname,
            'email' => $data->email ?? $data->nickname . '@youtube.com'
        ];
    }


    public function getAccessTokenFromDB($channel_id)
    {
        $youtubeChannel = SocialiteChannel::find($channel_id);
        return $youtubeChannel->access_token ?? null;
    }


    public function updateAccessTokenToDB($channel_id, $accessToken)
    {
        $youtubeChannel = SocialiteChannel::find($channel_id);
        $youtubeChannel->access_token = $accessToken;
        return $youtubeChannel->save();
    }

    */
}
