const mix = require("laravel-mix");

let source = "public/app/src/";
let build = "public/app/build/";

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("public/app/app.js", build)
    .js("public/app/api.js", build)
    // js("resources/js/app.js", "public/js")
    .js(source + "home.js", build)
    .js(source + "jenis_kegiatan.js", build)
    .js(source + "jenis_dokumen.js", build)
    .js(source + "jenis_kelompok_masyarakat.js", build)
    .js(source + "kelompok_masyarakat.js", build)
    .js(source + "paket_kegiatan.js", build)
    .js(source + "pic_kelompok_masyarakat.js", build)
    .js(source + "tahapan_pengajuan_kegiatan.js", build)
    .js(source + "tematik_kegiatan.js", build)
    .js(source + "sub_tematik_kegiatan.js", build)
    .js(source + "master_sub_tematik_kegiatan.js", build)
    .js(source + "satuan.js", build)
    .js(source + "pengajuan_kegiatan.js", build)
    .js(source + "jenis_komponen_rab.js", build)
    .js(source + "master_komponen_rab.js", build)
    .js(source + "master_data_bank.js", build)
    .js(source + "user_akseslh.js", build)
    .js(source + "log_jadwal_pembukaan.js", build)
    .js(source + "transaksi_penyaluran.js", build)
    .js(source + "master_data_indikator_laporan.js", build)
    .js(source + "master_user_jenis_kelompok.js", build)
    .js(source + "jenis_pekerjaan.js", build)
    .js(source + "pendidikan.js", build)
    .js(source + "banner_informasi.js", build)
    .version();

mix.disableNotifications();
