<?php

namespace ConfrariaWeb\SocialiteAutoPilot\Models;

use Illuminate\Database\Eloquent\Model;

class SocialiteMedia extends Model
{

    protected $table = 'socialite_medias';
    
    protected $fillable = [
        'socialite_account_id',
        'file',
        'data',
        'published_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
    ];

    public function account()
    {
        return $this->belongsTo(SocialiteAccount::class);
    }
}
