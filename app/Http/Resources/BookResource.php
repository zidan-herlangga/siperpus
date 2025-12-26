<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'cover_image'=> $this->cover_image,
            'slug'       => $this->slug,
            'author'     => $this->author,
            'publisher'  => $this->publisher,
            'year'       => $this->year,
            'isbn'       => $this->isbn,
            'category'   => $this->category,
            'synopsis'   => $this->synopsis,
            'shelf_code' => $this->shelf_code,
            'stock'      => $this->stock,
            'created_at' => $this->created_at,
        ];
    }
}
