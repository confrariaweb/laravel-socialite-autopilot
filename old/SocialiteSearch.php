<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Models;

use Illuminate\Database\Eloquent\Model;

class YoutubeSearch extends Model
{

    protected $fillable = [
        'youtube_channel_id', 
        'channel_id', 
    ];

    protected $casts = [
        'tags' => 'json',
    ];

    public function youtubeChannel()
    {
        return $this->belongsTo('ConfrariaWeb\YoutubeAutoPilot\Models\YoutubeChannel');
    }

}
