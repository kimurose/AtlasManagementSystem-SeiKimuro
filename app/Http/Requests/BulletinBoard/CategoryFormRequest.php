<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
            // 'main_category_id' => 'required|exists:main_categories,id',
        ];
    }

    public function messages()
    {
        return [
            'main_category_name.required' => 'メインカテゴリーは必須です。',
            'main_category_name.string' => 'メインカテゴリーは文字列でなければなりません。',
            'main_category_name.max' => 'メインカテゴリーは100文字以内で入力してください。',
            'main_category_name.unique' => '同じ名前のメインカテゴリーはすでに存在します。',
            'main_category_id.required' => 'メインカテゴリーは必須です。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
        ];
    }
}
