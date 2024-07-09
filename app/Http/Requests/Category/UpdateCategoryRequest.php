<?php

namespace App\Http\Requests\Category;

use App\Models\Category;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $category = Category::where('uuid', $this->route('uuid'))->firstOrFail();

        return [
            'title' => ['string', 'required', 'max:255', 'unique:categories,title,'.$category->id],
            'slug' => ['string', 'max:255'],
        ];
    }
}
