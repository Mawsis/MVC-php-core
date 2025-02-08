<?php

namespace app\resources;

use app\core\BaseResource;

class UserResource extends BaseResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->username,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
            'status' => $this->resource->status ? 'Active' : 'Inactive'
        ];
    }
}