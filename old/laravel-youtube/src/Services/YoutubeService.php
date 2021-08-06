<?php

namespace ConfrariaWeb\Youtube\Services;

use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Storage;

class YoutubeService
{

    protected $client;
    protected $youtube;

    public function __construct()
    {

        //$this->client = $this->providerFromGoogleClient($token);


        //$api_key = Config::get('services.youtube.api_key');
        //$this->client = new Google_Client();
        //$this->client->setDeveloperKey($api_key);
        //$this->youtube = new Google_Service_YouTube($this->client);
    }

    public function redirect()
    {
        return Socialite::driver('youtube')->scopes(Config::get('services.youtube.scopes'))->redirect();
    }

    public function callback()
    {
        return Socialite::driver('youtube')->user();
    }

    /**
     * Função para retornar todos os videos de um canal
     */
    public function listChannelVideos($channelId, $maxResults = 20, $order = 'date')
    {
        try {
            $videos = [];
            $searchResponse = $this->youtube->search->listSearch('id,snippet', array(
                'channelId' => $channelId,
                'order' => $order,
                'maxResults' => $maxResults,
                'type' => 'video'
            ));
            //dd($searchResponse->nextPageToken);
            foreach ($searchResponse['items'] as $searchResult) {
                if ($searchResult['id']['kind'] == 'youtube#video') {
                    $videos[] = $this->searchVideoById($searchResult->id->videoId)->items[0];
                }
            }
            return collect($videos);
        } catch (Google_Service_Exception $e) {
            return htmlspecialchars($e->getMessage());
        } catch (Google_Exception $e) {
            return htmlspecialchars($e->getMessage());
        }
    }

    /*public function listChannelVideos($channelId, $maxResults = 20, $order = 'date')
    {
        try {
            $searchResult = [];
            $videos = [];
            $channels = [];
            $playlists = [];
            $searchResponse = $this->youtube->search->listSearch('id,snippet', array(
                'channelId' => $channelId,
                'order' => $order,
                'maxResults' => $maxResults
            ));

            foreach ($searchResponse['items'] as $searchResult) {
                switch ($searchResult['id']['kind']) {
                    case 'youtube#video':
                        $videos[] = $searchResult;
                        break;
                    case 'youtube#channel':
                        $channels[] = $searchResult;
                        break;
                    case 'youtube#playlist':
                        $playlists[] = $searchResult;
                        break;
                }
            }
            return ['videos' => $videos, 'channels' => $channels, 'playlists' => $playlists];
        } catch (Google_Service_Exception $e) {
            return htmlspecialchars($e->getMessage());
        } catch (Google_Exception $e) {
            return htmlspecialchars($e->getMessage());
        }
    }*/

    public function searchVideoById($id)
    {
        try {
            $videosResponse = $this->youtube->videos->listVideos('id, snippet', [
                'id' => $id
            ]);
            return $videosResponse;
        } catch (Google_Service_Exception $e) {
            return htmlspecialchars($e->getMessage());
        } catch (Google_Exception $e) {
            return htmlspecialchars($e->getMessage());
        }
    }

    /**
     * Verifique se existe um vídeo do YouTube por seu ID.
     *
     * @param  int  $id
     *
     * @return bool
     */
    public function videoExists($id)
    {
        $response = $this->youtube->videos->listVideos('status', ['id' => $id]);
        if (empty($response->items)) return false;
        return true;
    }

    public function setAccessToken($accessToken)
    {
        if (is_null($this->client->getAccessToken())) {
            $this->client->setAccessToken($accessToken);
        }
    }

    /**
     * Lidar com o token de acesso
     *
     * @return void
     */
    public function handleAccessToken()
    {
        if (is_null($accessToken = $this->client->getAccessToken())) {
            throw new \Exception('An access token is required.');
        }

        if ($this->client->isAccessTokenExpired()) {
            // Se tivermos um "refresh_token"
            if (array_key_exists('refresh_token', $accessToken)) {
                // Atualize o token de acesso
                $this->client->refreshToken($accessToken['refresh_token']);
            }
        }
    }

    /**
     * Google_Client
     */

    public function upload($access_token, $data = [])
    {
        $videoPath = $data['video_path']?? NULL;
        if (Storage::missing($videoPath)) {
            $return = collect([
                'error' => true,
                'status' => 'Arquivo do video não encontrado'
            ]);
        }

        $client = $this->providerFromGoogleClient($access_token);

        // Defina um objeto que será usado para fazer todas as solicitações de API.
        $youtube = new Google_Service_YouTube($client);
        // Check to ensure that the access token was successfully acquired.
        return 's';
        if ($client->getAccessToken()) {
            try{
                // Create a snippet with title, description, tags and category ID
                // Create an asset resource and set its snippet metadata and type.
                // This example sets the video's title, description, keyword tags, and
                // video category.
                $snippet = new Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle("Test title");
                $snippet->setDescription("Test description");
                $snippet->setTags(array("tag1", "tag2"));
  
                // Numeric video category. See
                // https://developers.google.com/youtube/v3/docs/videoCategories/list
                $snippet->setCategoryId("22");
  
                // Set the video's status to "public". Valid statuses are "public",
                // "private" and "unlisted".
                $status = new Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = "public";
  
                // Associate the snippet and status objects with a new video resource.
                $video = new Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);
  
                // Specify the size of each chunk of data, in bytes. Set a higher value for
                // reliable connection as fewer chunks lead to faster uploads. Set a lower
                // value for better recovery on less reliable connections.
                $chunkSizeBytes = 1 * 1024 * 1024;
  
                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);
  
                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);
  
                // Create a MediaFileUpload object for resumable uploads.
                $media = new Google_Http_MediaFileUpload(
                    $client,
                    $insertRequest,
                    'video/*',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));
                
                // Leia o arquivo de mídia e carregue-o pedaço por pedaço.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }
                fclose($handle);
  
                // Se você quiser fazer outras chamadas após o upload do arquivo, defina setDefer de volta para false
                $client->setDefer(false);

                $return = collect([
                    'error' => false
                ]);
            } catch (Google_Service_Exception $e) {
                $return = collect([
                    'error' => true,
                    'status' => $e->getMessage()
                ]);
            } catch (Google_Exception $e) {
                $return = collect([
                    'error' => true,
                    'status' => $e->getMessage()
                ]);
            }
  
        }

        return 'teste';
    }


    public function providerFromGoogleClient($access_token){
        if(!Config::get('services.youtube.client_id') || !Config::get('services.youtube.client_secret')) {
            throw new \Exception('A Google "client_id" and "client_secret" must be configured.');
        }
        if(empty($access_token['token'])){
			throw new MissingTokenException('Token not Found');
		}
        try{
            $client = new Google_Client();
            $client->setClientId(Config::get('services.youtube.client_id'));
            $client->setClientSecret(Config::get('services.youtube.client_secret'));
            $client->setScopes(Config::get('services.youtube.scopes'));
            $client->setAccessType('offline');
            $client->setApprovalPrompt('force');
            $client->setRedirectUri(Config::get('services.youtube.redirect'));
            //$client->setAccessToken($access_token['token']);
            /*if ($client->isAccessTokenExpired()) {
                // Se tivermos um "refresh_token"
                if (array_key_exists('refresh_token', $access_token)) {
                    // Atualize o token de acesso
                    $client->refreshToken($access_token['refresh_token']);
                    //$client->refreshToken($token->getRefreshToken());
                }
            }*/
            return $client;
		}catch(\Exception $e){
	        throw new InvalidTokenException('Não é possível verificar o token salvo. O usuário revogou os privilégios ou criou um novo token.');
	    }
	}


}
