<?php

namespace ConfrariaWeb\YoutubeAutoPilot\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class YoutubeChannel extends Model
{

    protected $fillable = [
        'user_id',
        'access_token',
        'channel_id',
        'channel_title',
        'avatar'
    ];

    protected $casts = [
        'access_token' => 'json'
    ];

    public function monitoredChannels()
    {
        return $this->hasMany(YoutubeChannelMonitoring::class);
    }

    public function videos()
    {
        return $this->hasMany(YoutubeVideo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
