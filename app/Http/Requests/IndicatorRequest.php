<?php

namespace App\Http\Requests;

use App\Rules\InArrayRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndicatorRequest extends FormRequest
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
            'type' => [new InArrayRule(['mix', 'string', 'guid', 'int'])],
            'length' => 'numeric|min:1|max:100'
        ];
    }

    /**
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        $errors = [];
        foreach ($validator->errors()->messages() as $key => $message){
            $errors[$key] = implode(',', $message);
        }
        throw new HttpResponseException(response()->json(['status' => 400, 'errors' => $errors], 400));
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'min' => ':attribute не должен быть меньше :min.',
            'max' => ':attribute не должен превышать :max.',
            'numeric' => ':attribute должен быть числом.',
        ];
    }
}
