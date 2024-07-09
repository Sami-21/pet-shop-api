<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = Product::where('uuid', $this->route('uuid'))->firstOrFail();

        return [
            'category_uuid' => ['required', 'string', 'exists:categories,uuid'],
            'title' => ['string', 'required', 'max:255', 'unique:products,title,' . $product->uuid],
            'price' => ['required', 'decimal:2', 'min:0'],
            'brand' => ['required', 'string', 'exists:brands,uuid'],
            'image' => ['required', 'string', 'exists:files,uuid'],
            'description' => ['required', 'string'],
        ];
    }
}
