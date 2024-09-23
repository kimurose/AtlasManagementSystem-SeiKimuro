<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        // old_year, old_month, old_day から birth_day を組み立てる
        $this->merge([
            'birth_day' => sprintf('%04d-%02d-%02d', $this->old_year, $this->old_month, $this->old_day)
        ]);
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
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
            'under_name_kana' => 'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
            'mail_address' => 'required|email|unique:users,mail_address|max:100',
            'sex' => 'required|integer|in:1,2,3',
            'birth_day' => 'required|date|date_format:Y-m-d|after_or_equal:2000-01-01|before_or_equal:today',
            'old_year' => 'required|integer|min:2000|max:2024',
            'old_month' => 'required|integer|min:1|max:12',
            'old_day' => 'required|integer|min:1|max:31',
            'role' => 'required|integer|in:1,2,3,4',
            'password' => 'required|string|between:8,30|confirmed',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
        'over_name.required' => '姓は必須です。',
        'over_name.string' => '姓は文字列でなければなりません。',
        'over_name.max' => '姓は10文字以内で入力してください。',
        
        'under_name.required' => '名は必須です。',
        'under_name.string' => '名は文字列でなければなりません。',
        'under_name.max' => '名は10文字以内で入力してください。',
        
        'over_name_kana.required' => '姓（カタカナ）は必須です。',
        'over_name_kana.string' => '姓（カタカナ）は文字列でなければなりません。',
        'over_name_kana.regex' => '姓（カタカナ）はカタカナのみを使用してください。',
        'over_name_kana.max' => '姓（カタカナ）は30文字以内で入力してください。',
        
        'under_name_kana.required' => '名（カタカナ）は必須です。',
        'under_name_kana.string' => '名（カタカナ）は文字列でなければなりません。',
        'under_name_kana.regex' => '名（カタカナ）はカタカナのみを使用してください。',
        'under_name_kana.max' => '名（カタカナ）は30文字以内で入力してください。',
        
        'mail_address.required' => 'メールアドレスは必須です。',
        'mail_address.email' => 'メールアドレスは有効なメールアドレス形式でなければなりません。',
        'mail_address.unique' => 'このメールアドレスはすでに使用されています。',
        'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
        
        'sex.required' => '性別は必須です。',
        'sex.in' => '性別は「male」、「female」、「other」のいずれかでなければなりません。',
        
        'birth_day.required' => '生年月日は必須です。',
        'birth_day.date' => '生年月日は有効な日付でなければなりません。',
        'birth_day.date_format' => '生年月日は「Y-m-d」形式で入力してください。',
        'birth_day.after_or_equal' => '生年月日は2000年1月1日以降の日付でなければなりません。',
        'birth_day.before_or_equal' => '生年月日は今日の日付までの日付でなければなりません。',
        // 'old_year.required' => '年は必須です。',
        // 'old_year.integer' => '年は整数でなければなりません。',
        // 'old_year.min' => '年は2000以上でなければなりません。',
        // 'old_year.max' => '年は2024以下でなければなりません。',
        
        // 'old_month.required' => '月は必須です。',
        // 'old_month.integer' => '月は整数でなければなりません。',
        // 'old_month.min' => '月は1以上でなければなりません。',
        // 'old_month.max' => '月は12以下でなければなりません。',
        
        // 'old_day.required' => '日は必須です。',
        // 'old_day.integer' => '日は整数でなければなりません。',
        // 'old_day.min' => '日は1以上でなければなりません。',
        // 'old_day.max' => '日は31以下でなければなりません。',
        
        'role.required' => '役割は必須です。',
        'role.in' => '役割は「教師(国語)」、「教師(数学)」、「教師(英語)」、「生徒」のいずれかでなければなりません。',
        
        'password.required' => 'パスワードは必須です。',
        'password.string' => 'パスワードは文字列でなければなりません。',
        'password.between' => 'パスワードは8文字以上30文字以内でなければなりません。',
        'password.confirmed' => 'パスワード確認用と一致しません。',
    ];
    }
}
