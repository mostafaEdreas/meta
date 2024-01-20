<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseProductRequest extends FormRequest
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
            'product_id' =>'required',
            'quantity'=> 'required',
            'purchase_price'=> 'required',
            'sale_price'=> 'required',
            'product_id.*' =>'required|exists:products,id',
            'quantity.*'=> 'required|numeric|gt:0',
            'purchase_price.*'=> 'required|numeric',
            'sale_price.*'=> 'required|numeric',
            'discount_p.*'=> 'nullable|numeric',
            'discount_type_p.*'=> 'nullable|in:percent,amount',
        ];
    }
    public function messages(): array{
        return [
            'product_id.*.required'=> 'يجب اختيار صنف او مسح الصنف من الفاتوره',
            'product_id.required'=> 'يجب اختيار صنف',
            'product_id.*.exists'=> 'الصنف غير مسجل',
            'quantity.*.required'=> 'الكميه مطلوبه',
            'quantity.required'=> 'الكميه مطلوبه',
            'quantity.*.gt'=> 'يجب ان لا تقل كميه الصنف عن 1',
            'quantity.*.numeric'=> 'الكميه يجب ان تكون رقم',
            'purchase_price.*.required'=>'سعر الشراء مطلوب',
            'purchase_price.required'=>'سعر الشراء مطلوب',
            'purchase_price.*.numeric'=> 'سعر الشراء يجب ان يكون رقم',
            'sale_price.*.required'=>'سعر البيع مطلوب',
            'sale_price.required'=>'سعر البيع مطلوب',
            'sale_price.*.numeric'=> 'سعر البيع يجب ان يكون رقم',
            'discount_p.*.required'=>'الخصم مطلوب',
            'discount_p.*.numeric'=> 'الخصم يجب ان يكون رقم',
            'discount_type_P.*.in'=>'نوع خصم الصنف غير صحيح'

        ] ;
    }
}
