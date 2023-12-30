<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->middleware("IsAdmin");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>['required'],
            "email"=> ["required","email",'unique:users,email,'.$this->id],
            'phone'=>['required','numeric','unique:users,phone'.$this->id],
            'password'=>['required','min:6','max:250','confirmed'],
        ];
    }

    public function messages(){
        return [
            'phone.required'=> 'يجب ادخال رقم الهاتف ',
            'phone.numeric'=> 'رقم هاتف غير صالح',
            'password.required'=> 'يجب ادخال الرقم السرى',
            'password.min'=> 'الرقم السرى يجب ان لا يقل عن 6 خانات',
            'password.max'=> 'الرقم السرى يجب ان لا يزيد عن 250 خانة',
            'name.required'=> 'يجب ادخال اسم المستخدم',
            'email.required'=> 'يجب ادخال البريد الالكترونى',
            'email.email'=> 'البريد الالكترونى غير صحيح',
            'email.unique'=> 'البريد الالكترونى مستخدم من قبل',
            'password.confirmed'=> 'كلمتان السر غير متطابقتان',
            'phone.uniqe'=> 'رقم الهاتف مستخدم من قبل',
        ] ;
    }
}
