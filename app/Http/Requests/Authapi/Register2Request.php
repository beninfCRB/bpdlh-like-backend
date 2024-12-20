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
            'provinsi_kelompok_masyarakat_id'              => 'required',
            'kabupaten_kelompok_masyarakat_id'             => 'required',
            'kecamatan_kelompok_masyarakat_id'             => 'required',
            'kelurahan_kelompok_masyarakat_id'             => 'required',
            'profil_kelompok'               => 'required|file|mimes:pdf|max:2048',
            'foto_ktp'                      => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'foto_selfie'                   => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'nama_pic'                  => 'required|max:255|string',
            // 'nomor_identitas_pic'       => 'required|string|min:16|max:16|unique:data_pic_kelompok_masyarakats,nomor_identitas_pic',
            'nomor_identitas_pic'       => ['required', 'string', 'min:16', 'max:16', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats')->whereNull('deleted_at')],
            'nomor_npwp_pic'            => 'nullable',
            'alamat_pic'                => 'required|string|max:255',
            'provinsi_pic'              => 'required',
            'kabupaten_pic'             => 'required',
            'kecamatan_pic'             => 'required',
            'kelurahan_pic'             => 'required',
            'tempat_lahir'              => 'required',
            'tanggal_lahir'             => 'required|date',
            'agama_id'                  => 'required|exists:agamas,id',
            'status_perkawinan_id'      => 'required|exists:status_pernikahans,id',
            'nama_gadis_ibu_kandung'    => 'required',
            'jenis_pekerjaan_id'        => 'required|exists:jenis_pekerjaans,id',
            'nohp_pic'                  => 'required|unique:data_pic_kelompok_masyarakats,nohp_pic',
            // 'email_pic'                 => 'required|email|unique:data_pic_kelompok_masyarakats,email_pic|unique:user_akseslhs,email',
            'email_pic'                 => ['required', 'email', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats')->whereNull('deleted_at')],
            'kode_aktivasi'             => 'required',
        ];
        /*'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kelurahan_id' => 'required|exists:kelurahan,id',
            'user_id' => [
                'required',
                'exists:users,id', 
                // Validasi agar hanya satu user yang bisa terdaftar pada kombinasi provinsi_id, kabupaten_id, kecamatan_id, kelurahan_id
                Rule::unique('kelompok_masyarakat')
                    ->where(function ($query) {
                        return $query
                            ->where('provinsi_id', $this->provinsi_id)
                            ->where('kabupaten_id', $this->kabupaten_id)
                            ->where('kecamatan_id', $this->kecamatan_id)
                            ->where('kelurahan_id', $this->kelurahan_id);
                    })
            ],
            */
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
