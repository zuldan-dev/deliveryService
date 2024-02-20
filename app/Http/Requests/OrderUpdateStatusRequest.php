<?php

namespace App\Http\Requests;

use App\Rules\OrderValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderUpdateStatusRequest extends OrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return OrderValidationRules::updateStatusRules();
    }
}
