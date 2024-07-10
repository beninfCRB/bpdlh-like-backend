<?php

namespace App\Http\Requests\Authapi;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\InitialRequestValidation;

class RegisterRequest extends FormRequest
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
            //
            // 'id_kelompok_masyarakat'            => 'required|exists:akseslh_kelompok_masyarakats',
            'kelompok_masyarakat_id'    => 'required',
            // 'email_user_eksternal'              => 'required|email|unique:akseslh_user_eksternals,email_user_eksternal,' . ($this->user ? $this->user->id : 'NULL'),
            'email_user_eksternal'              => 'required|email',
            'nama_user_eksternal'               => 'required|max:255|string',
            'jenis_identitas_user_eksternal'    => 'required',
            'nomor_identitas_user_eksternal'    => 'required|string|max:20',
            'nomor_hp_user_eksternal'           => 'required',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'akseslh_kelompok_masyarakat_id.required'   => 1,
    //         'email_user_eksternal.required'             => 1,
    //         'nama_user_eksternal.required'              => 1,
    //         'nomor_identitas_user_eksternal.required'   => 1,
    //     ];
    // }
}
