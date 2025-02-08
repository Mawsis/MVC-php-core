<?php

namespace app\core;

abstract class BaseResource
{
    protected $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }
    public abstract function toArray(): array;
    public static function collection(array $resources): array
    {
        foreach ($resources as $key => $resource) {
            $resources[$key] = (new static($resource))->toArray();
        }
        return $resources;
    }

    public static function make($resource)
    {
        return (new static($resource))->toArray();
    }
}