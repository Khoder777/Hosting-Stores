<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productSubCategoryValueResource extends JsonResource
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
            'value' => $this->value,
            'product_id' => $this->Product->name,
            'sub_category_property_id' => $this->SubCategoeyProperty->name ?? 'null',
            'image' => $this->image,
            'created_at' => $this->modifyCreatedAt($this->created_at),
            'updated_at' => $this->modifyCreatedAt($this->updated_at),
        ];
    }
    private function modifyCreatedAt($value)
    {
        $createdAt = Carbon::parse($value);
        $now = Carbon::now();
        $diff = $now->floatDiffInRealSeconds($createdAt);
        $duration = CarbonInterval::seconds($diff)->cascade();
        return "Created  " . $duration->forHumans(['short' => true]) . " ago";
    }
}
