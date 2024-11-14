<?php

namespace App\Http\Requests\Cms\Akseslh\Pendidikan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendidikanRequest extends FormRequest
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
            'pendidikan'   => 'required|unique:pendidikans,pendidikan,' . $this->route('pendidikan')
        ];
    }
}
