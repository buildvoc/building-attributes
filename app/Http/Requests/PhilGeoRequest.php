<?php

namespace App\Http\Requests;

use App\Models\ApiJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PhilGeoRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'osid'              => 'required|string|max:255',
            'toid'              => 'required|string|max:255',
            'height_max'        => 'required|numeric',
            'symbol'            => 'required|numeric',
            'geom'              => 'required',
            'geom.coordinates'  => 'required|array',
            'geom.type'         => 'required|string|max:255',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiJsonResponse::sendErrors($validator->errors()));
    }
}
