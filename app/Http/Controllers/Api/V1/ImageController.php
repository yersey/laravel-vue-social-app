<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\ImageRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ImageResource;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function store(ImageRequest $request): JsonResponse
    {
        $image = $request->safe()->image;
        $path = $image->store('images');

        return ImageResource::make(['image' => $path])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
