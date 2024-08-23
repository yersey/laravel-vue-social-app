<?php

namespace App\DataTransferObjects;

use App\Http\Requests\CommentRequest;

readonly class CommentDto
{
    public function __construct(
        public string $content,
        public string $userId,
    ) {}

    public static function fromRequest(CommentRequest $request): CommentDto
    {
        return new self(
            content: $request->safe()->content,
            userId: auth()->id()
        );
    }
}