<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Models;

use Illuminate\Database\Eloquent\Model;

class YoutubeVideo extends Model
{

    protected $fillable = [
        'youtube_channel_id', 
        'video_id', 
        'max_results', 
        'title', 
        'description', 
        'tags',
        'downloaded_at',
        'published_at'
    ];

    protected $casts = [
        'tags' => 'json',
    ];

    public function channel()
    {
        return $this->belongsTo('ConfrariaWeb\YoutubeAutoPilot\Models\YoutubeChannel');
    }

}
