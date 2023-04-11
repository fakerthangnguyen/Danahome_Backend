<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use function PHPSTORM_META\elementType;

class CreateRoomsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {

           if(request()->isMethod('post')){
                return [
                    'name'  =>  'required|string|max:258',
                    'title'  =>  'required|string|max:258',
                    'description'  =>  'required||string|max:258',
                    'address'  =>  'required|max:258',
                    'price'  =>  'required|numeric|min:0',
                    'area'  =>  'required|numeric|min:0',
                    'image'  =>  'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                    'category_id'  =>  'required|exists:categories,id',
                    // 'account_id'  =>  'required|exists:accounts,id',
                    'city'  =>  'required|string|max:258',
                    'district'  =>  'required|string|max:258',
                    'ward'  =>  'required|string|max:258',
                    'status' =>  'required|boolean',
                ];
           }else {
                return [
                    'name'  =>  'required|string|max:258',
                    'title'  =>  'required|string|max:258',
                    'description'  =>  'required||string|max:258',
                    'address'  =>  'required|max:255',
                    'price'  =>  'required|numeric|min:0',
                    'area'  =>  'required|numeric|min:0',
                    'image'  =>  'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
                    'category_id'  =>  'required|exists:categories,id',
                    'account_id'  =>  'required|exists:accounts,id',
                    'city'  =>  'required|string|max:258',
                    'district'  =>  'required|string|max:258',
                    'ward'  =>  'required|string|max:258',
                    'status' =>  'required|boolean',
                ];
           }

    }
}
