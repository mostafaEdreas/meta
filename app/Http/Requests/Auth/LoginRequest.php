<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!auth()->check()){
            return true;
         
        }
        return false;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone'=>['required','numeric'],
            'password'=>['required','min:6','max:250'],
        ];
    }

    public function messages(){
        return [
            'phone.required'=> 'يجب ادخال رقم الهاتف ',
            'phone.numeric'=> 'رقم هاتف غير صالح',
            'password.required'=> 'يجب ادخال الرقم السرى',
            'password.min'=> 'الرقم السرى يجب ان لا يقل عن 6 خانات',
            'password.max'=> 'الرقم السرى يجب ان لا يزيد عن 250 خانة',
        ] ;
    }
}

