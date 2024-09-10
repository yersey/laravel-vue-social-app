<?php

namespace App\Hateoas;

use App\Models\User;

abstract class Hateoas
{
    public function __construct(
        protected $resource,
        protected ?User $user
    ) {}

    protected function link(string $rel, string $type, string $href): array
    {
        return [
            'rel' => $rel,
            'type' => $type,
            'href' => $href
        ];
    }
}
