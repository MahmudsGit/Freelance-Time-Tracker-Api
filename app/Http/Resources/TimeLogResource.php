<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'hours' => $this->hours,
            'description' => $this->description,
            'tag' => $this->tag,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
