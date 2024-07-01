<?php

namespace App\Http\Requests\Cms\Akseslh;

use App\Http\Requests\InitialRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class JenisKegiatanRequest extends FormRequest
{
    use InitialRequestValidation;
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
            'jenis_kegiatan' =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'jenis_kegiatan.required'   =>  'Jenis Kegiatan Tidak boleh kosong',
        ];
    }
}
