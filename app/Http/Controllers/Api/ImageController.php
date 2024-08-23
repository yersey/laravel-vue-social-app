<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\ImageRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function store(ImageRequest $request): JsonResponse
    {
        $image = $request->safe()->image;
        $path = 'public/' . $image->store('images', 'public');

        return ImageResource::make(['image' => $path])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
