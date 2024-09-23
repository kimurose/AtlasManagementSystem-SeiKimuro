<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostEditRequest extends FormRequest
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
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:5000',
        ];
    }

    public function messages()
    {
        return [
            'post_title.required' => 'タイトルは必須です。',
            'post_title.string' => 'タイトルは文字列でなければなりません。',
            'post_title.max' => 'タイトルは100文字以内でなければなりません。',
            'post_body.required' => '本文は必須です。',
            'post_body.string' => '本文は文字列でなければなりません。',
            'post_body.max' => '本文は5000字以内でなければなりません。',
            'comment.required' => 'コメントは必須です。',
        ];
    }
}
