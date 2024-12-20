<?php

namespace App\Http\Requests\Cms\Akseslh\JenisPekerjaan;

use Illuminate\Foundation\Http\FormRequest;

class CreateJenisPekerjaanRequest extends FormRequest
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
            'jenis_pekerjaan'   => 'required|unique:jenis_pekerjaans,jenis_pekerjaan'
        ];
    }
}
