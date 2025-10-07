<?php

namespace App\GP247\Plugins\LoginSocial\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type',
        'user_id',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * Get the user that owns the social account.
     * This is a polymorphic relationship
     */
    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
