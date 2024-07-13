<?php

namespace Rzb\SocialAuth\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

interface Resourceable
{
    public function toResource(): JsonResource;
}
