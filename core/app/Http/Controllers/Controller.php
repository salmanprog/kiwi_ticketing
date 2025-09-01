<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse(int $code = 200, string $message = 'success', $data = [])
    {
        $isPaginated = $this->isPaginated($data); // Custom method to check if paginated
        $links = $isPaginated ? $this->paginateLinks($data) : null;
        $results = $isPaginated ? $data->items() : $data;

        if (property_exists($this, 'collection') && $this->collection) {
            $resource = $this->loadResource(); // Your custom logic to get resource class
            $results = (new $resource($results))->toArray(request());
        }

        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $results,
        ];

        if ($links !== null) {
            $response['pagination'] = $links;
        }

        return response()->json($response, $code);
    }
}
