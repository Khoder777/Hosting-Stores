<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => number_format($this->price, 2) . ' $',
            'desc' => $this->desc,
            'rate' => $this->rate,
            'category' => $this->Category->name,
            'market' => $this->Market->name,
            'created_at' => $this->modifyCreatedAt($this->created_at),
            'updated_at' => $this->modifyCreatedAt($this->updated_at),
        ];
    }
    private function modifyCreatedAt($created_at)
    {
        $createdAt = Carbon::parse($created_at);
        $now = Carbon::now();
        $diff = $now->floatDiffInRealSeconds($createdAt);
        $duration = CarbonInterval::seconds($diff)->cascade();
        return "Created  " . $duration->forHumans(['short' => true]) . " ago";
    }
}
