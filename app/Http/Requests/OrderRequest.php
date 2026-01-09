<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',

            // Validate each item in the items array
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Custom error messages for validation
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user for this order.',
            'user_id.exists' => 'Selected user does not exist.',

            'items.required' => 'You must add at least one product to place the order.',
            'items.array' => 'Invalid products data.',

            'items.*.category_id.required' => 'Please select a category for this product.',
            'items.*.category_id.exists' => 'Selected category is invalid.',

            'items.*.product_id.required' => 'Please select a product.',
            'items.*.product_id.exists' => 'Selected product is invalid.',

            'items.*.quantity.required' => 'Please enter quantity.',
            'items.*.quantity.integer' => 'Quantity must be a number.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',

            'discount.numeric' => 'Discount must be a valid number.',
            'discount.min' => 'Discount cannot be negative.',
        ];
    }
}
