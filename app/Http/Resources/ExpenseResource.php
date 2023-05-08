<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'requester' => UserResource::make($this->requester),
            'approver' => UserResource::make($this->approver),
            'company' => CompanyResource::make($this->company),
            'status' => $this->status,
            'description' => $this->description,
            'value' => $this->value,
            'date' => $this->date,
            'approval_date' => $this->approval_date,
            'reason_for_rejection' => $this->reason_for_rejection,
        ];

    }
}
