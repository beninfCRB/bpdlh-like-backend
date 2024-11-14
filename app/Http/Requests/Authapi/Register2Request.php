<?php

namespace App\Http\Requests\Authapi;

use Illuminate\Foundation\Http\FormRequest;

class Register2Request extends FormRequest
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
            'nama_pic'                  => 'required|max:255|string',
            'nomor_identitas_pic'       => 'required|string|max:20',
            'nomor_npwp_pic'            => 'required',
            'alamat_pic'                => 'required',
            'kelurahan_pic'             => 'required',
            'kecamatan_pic'             => 'required',
            'kabupaten_pic'             => 'required',
            'provinsi_pic'              => 'required',
            'tempat_lahir'              => 'required',
            'tanggal_lahir'             => 'required|date',
            'agama'                     => 'required',
            'status_perkawinan'         => 'required',
            'nama_gadis_ibu_kandung'    => 'required',
            'jenis_pekerjaan'           => 'required',
            'kelompok_masyarakat_id'    => 'required',
            'email_pic'                 => 'required|email',
            'jenis_identitas_pic'       => 'required',
            'nohp_pic'                  => 'required',
        ];
    }
}
