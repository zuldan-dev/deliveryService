<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $id
 * @property string $status
 * @property Driver $driver
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'status' => $this->status,
        ];

        if ($this->status === OrderStatusEnum::processed->value && $this->driver) {
            $data['driver'] = new DriverResource($this->driver);
        }

        return $data;
    }
}
