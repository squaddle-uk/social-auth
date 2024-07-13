<?php

namespace Rzb\SocialAuth\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

trait Resourceable
{
    public function toResource(): JsonResource
    {
        // We assume we're using the Model and Resource namespaces of a typical
        // Laravel app. Otherwise, we fall back to the generic JsonResource
        // class. This helps with testing as we can mimic the structure.
        $resource = Str::of(get_class($this))
            ->replace('Models', 'Http\\Resources')
            ->append('Resource')
            ->value;

        if (is_subclass_of($resource, JsonResource::class)) {
            return new $resource($this);
        }

        return new JsonResource($this);
    }
}
