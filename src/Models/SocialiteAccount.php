<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialiteAccount extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'social_id',
        'data',
        'provider',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
