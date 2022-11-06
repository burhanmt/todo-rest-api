<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessfulResponse extends JsonResource
{
    public function toArray($request)
    {
        // Adding success field to the success payload.
        return [
            'success' => true,
        ] + $this->resource;
    }
}
