<?php

namespace App\Http\Controllers;

use App\Services\UrlShortener\Contracts\UrlShortenerInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlController extends Controller
{
    public function __invoke(
        Request $request,
        UrlShortenerInterface $urlShortener,
    ) : JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validate->fails()){
            return response()->json(
                [
                    'errors' => $validate->errors(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $urlShort = $urlShortener->shortUrl($request->get('url'));
        } catch (Exception $e){
            return response()->json(
                [
                    'errors' => [
                        'error' => [
                            $e->getMessage(),
                        ],
                    ],
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'url' => $urlShort,
        ]);
    }
}
