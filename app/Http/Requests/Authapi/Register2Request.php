<?php

namespace App\Http\Requests\Authapi;

use Carbon\Carbon;
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
            'jenis_kelompok_masyarakat_id'  => 'required|exists:jenis_kelompok_masyarakats,id',
            'kelompok_masyarakat'           => 'required',
            'profil_kelompok'               => 'nullable|file|mimes:pdf|max:2048',
            'foto_ktp'                      => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'foto_selfie'                   => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'nama_pic'                  => 'required|max:255|string',
            'nomor_identitas_pic'       => 'required|string|min:16|max:16',
            'nomor_npwp_pic'            => 'required',
            'alamat_pic'                => 'required|string|max:255',
            'provinsi_pic'              => 'required',
            'kabupaten_pic'             => 'required',
            'kecamatan_pic'             => 'required',
            'kelurahan_pic'             => 'required',
            'tempat_lahir'              => 'required',
            'tanggal_lahir'             => 'required|date',
            'agama_id'                  => 'required',
            'status_perkawinan_id'      => 'required',
            'nama_gadis_ibu_kandung'    => 'required',
            'jenis_pekerjaan_id'        => 'required|exists:jenis_pekerjaans,id',
            'nohp_pic'                  => 'required|unique:data_pic_kelompok_masyarakats,nohp_pic',
            'email_pic'                 => 'required|email|unique:data_pic_kelompok_masyarakats,email_pic|unique:user_akseslhs,email',
            'kode_aktivasi'             => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email_pic');
            $token = $this->input('kode_aktivasi');

            $record = \DB::table('users_verify_tokens')
                ->where('user_email', $email)
                ->where('token', $token)
                ->first();

            if (!$record) {
                $validator->errors()->add('token', 'Token tidak valid.');
            } elseif (Carbon::parse($record->expired_at)->isPast()) {
                $validator->errors()->add('token', 'Token sudah kedaluwarsa.');
            }
        });
    }
}
