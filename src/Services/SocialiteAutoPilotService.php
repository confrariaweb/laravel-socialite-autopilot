<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Services;

use Auth;
use ConfrariaWeb\SocialiteAutoPilot\Services\SocialiteService;
use ConfrariaWeb\SocialiteAutoPilot\Jobs\DownloadVideo;
use ConfrariaWeb\SocialiteAutoPilot\Jobs\PublishVideoJob;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteChannel;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteSearch;
use ConfrariaWeb\SocialiteAutoPilot\Models\SocialiteVideo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialiteAutoPilotService
{

    protected $youtubeService;

    function __construct(SocialiteService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function redirect()
    {
        return resolve('SocialiteService')->redirect();
    }

    public function callback(Request $request)
    {
        $accessToken = resolve('SocialiteService')->callback();
        $data['user_id'] = Auth::id();
        $data['access_token'] = $accessToken;
        $data['channel_id'] = $accessToken->id;
        $data['channel_title'] = $accessToken->nickname;
        $data['avatar'] = $accessToken->avatar;
        return $this->updateOrCreateChannel($data);
    }

    /**
     * Publicar videos
     */
    public function publishVideos($data = []){
        $videos = SocialiteVideo::whereNull('published_at')->whereNotNull('downloaded_at')->get();
        foreach($videos as $video){
            if (Storage::missing($video->path)) {
                continue;
            }
            PublishVideoJob::dispatch($video);
        }
    }

    /**
     * Publicar o video
     */
    public function publishVideo(SocialiteVideo $video){
        if (Storage::missing($video->path)) {
            return false;
        }
        $path = Storage::path($video->path);
        $uuu = new SocialiteService();
        $uuu->upload($video->channel->access_token, ['video_path' => $path]);
        return true;
    }

    /**
     * Saves the access token to the database.
     *
     * @param  string  $accessToken
     */
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

    /**
     * Busca videos do canal cadastrrado em search channel
     */
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
                /* Abre um job para baixar o video */
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
    }*/

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







    /**
     * Get the latest access token from the database.
     *
     * @return string
     */
    public function getAccessTokenFromDB($channel_id)
    {
        $youtubeChannel = SocialiteChannel::find($channel_id);
        return $youtubeChannel->access_token ?? null;
    }

    /**
     * Salva o token de acesso no banco de dados.
     *
     * @param  string  $accessToken
     */
    public function updateAccessTokenToDB($channel_id, $accessToken)
    {
        $youtubeChannel = SocialiteChannel::find($channel_id);
        $youtubeChannel->access_token = $accessToken;
        return $youtubeChannel->save();
    }
}
