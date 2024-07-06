<?php

namespace Rzb\SocialAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rzb\SocialAuth\Database\Factories\SocialAccountFactory;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = ['provider_user_id', 'provider'];

    public function sociable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return SocialAccountFactory::new();
    }
}
