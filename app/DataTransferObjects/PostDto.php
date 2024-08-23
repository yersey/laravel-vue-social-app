<?php

namespace App\DataTransferObjects;

use App\Http\Requests\PostRequest;

readonly class PostDto
{
    public function __construct(
        public string $content,
        public string $userId,
    ) {}

    public static function fromRequest(PostRequest $request): PostDto
    {
        return new self(
            content: $request->safe()->content,
            userId: auth()->id()
        );
    }
}