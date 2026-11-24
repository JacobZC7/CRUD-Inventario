<?php

namespace App\Http\Requests;

use App\Enums\MovementType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-inventory') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::enum(MovementType::class)],
            'quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
