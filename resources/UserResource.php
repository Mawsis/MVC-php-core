<?php

namespace app\resources;

use app\core\BaseResource;

class UserResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
        ];
    }
}