<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'publication_date' => $this->publication_date,
            'borrowed' => $this->borrowed,
            'user' => new UserResource($this->user),
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
