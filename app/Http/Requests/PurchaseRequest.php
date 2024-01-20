<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'reference'=> 'required|unique:purchases,reference,'.$this->id,
            'supplier_id'=>'required|exists:suppliers,id',
            'discount' => 'nullable|numeric',
            'store_id'=>'required|exists:stores,id',
            'discount_type'=> 'nullable|in:percent,amount',
            'type'=> 'required|in:purchase,returned',
        ];
    }
    public function messages(): array{
        return [
            'reference.required'=> 'رقم الفاتوره غير صحيح',
            'reference.unique'=> 'رقم الفاتوره غير صحيح',
            'supplier_id.required'=> 'المورد مطلوب',
            'supplier_id.exists'=> 'المورد غير مسجل',
            'discount.numeric'=> 'قيمة الخصم غير صحيحة',
            'store_id.required'=> 'المخزن مطلوب',
            'store_id.exists'=> 'المخزن غير مسجل',
            'discount_type.in'=> 'نوع الخصم غير صحيح',
            'type.in'=> 'نوع الفاتورة غير صحيح',
        ] ;
    }
}
