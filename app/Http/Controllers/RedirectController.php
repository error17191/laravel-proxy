<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RedirectController extends Controller
{
    const REDIRECT_URL_HEADER = 'Redirect-Base-Url';

    public function __invoke(Request $request)
    {
        $requestOptions = [
            'query' => $request->query(),
            'headers' => $this->transformHeaders($request->header()),
        ];

        if ($request->getContent()) {
            $requestOptions['body'] = $request->getContent();
        }

        $client = new Client();
        try {
            $response = $client->request($request->method(), $this->url($request), $requestOptions);
        } catch (RequestException $exception) {
            return $this->transformResponse($exception->getResponse());
        }
        return $this->transformResponse($response);
    }

    private function transformResponse($response)
    {
        return response($response->getBody()->getContents(), $response->getStatusCode(), $this->transformHeaders($response->getHeaders()));
    }

    private function transformHeaders($headers)
    {
        $transformedHeaders = [];
        foreach ($headers as $header => $values) {
            $value = implode(';', $values);
            if (!$value || Str::lower($header) == 'host' || Str::lower($header) == Str::lower(self::REDIRECT_URL_HEADER)) {
                continue;
            }
            $transformedHeaders[Str::title($header)] = $value;
        }

        return $transformedHeaders;
    }

    private function url(Request $request)
    {
        $baseUrl = $request->header('Redirect-Base-Url') ?: env('REDIRECT_URL');
        return $baseUrl . $request->getRequestUri();
    }
}
