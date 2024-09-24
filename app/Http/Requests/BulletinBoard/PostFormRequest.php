<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
            // 'post_category_id' => 'required|exists:sub_category,id',
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:5000',
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category',
        ];
    }

    public function messages(){
        return [
            'post_category_id.required' => 'サブカテゴリーは必須です。',
            'post_category_id.exists' => '選択されたサブカテゴリーは存在しません。',
            'post_title.required' => 'タイトルは必須です。',
            'post_title.string' => 'タイトルは文字列でなければなりません。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '内容は必須です。',
            'post_body.string' => '内容は文字列でなければなりません。',
            'post_body.max' => '最大文字数は5000文字です。',
            'main_category_name.required' => 'メインカテゴリーは必須です。',
            'main_category_name.string' => 'メインカテゴリーは文字列でなければなりません。',
            'main_category_name.max' => 'メインカテゴリーは100文字以内で入力してください。',
            'main_category_name.unique' => '同じ名前のメインカテゴリーはすでに存在します。',
            'main_category_id.required' => 'メインカテゴリーは必須です。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
            // サブカテゴリーのエラーメッセージ
            'sub_category_name.required' => 'サブカテゴリーは必須です。',
            'sub_category_name.string' => 'サブカテゴリーは文字列でなければなりません。',
            'sub_category_name.max' => 'サブカテゴリーは100文字以内で入力してください。',
            'sub_category_name.unique' => '同じ名前のサブカテゴリーはすでに存在します。',
        ];
    }
}