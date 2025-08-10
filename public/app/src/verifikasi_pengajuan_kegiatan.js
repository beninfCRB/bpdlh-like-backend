"use strict";

import Swal from "sweetalert2";
import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
import axios from "axios";

jQuery(document).ready(function () {
    let route = $("#verifikasi-pengajuan-kegiatan-route").val();
    let pengajuanKegiatanROute = $("#pengajuan-kegiatan-route").val();
    let appRoute = $("#app-route").val();

    let lastTriggerButton = null;

    let item = null;
    let kelompokMasyarakat = $("#kelompok-masyarakat");
    let namaPic = $("#nama-pic");
    let idPengajuan = null;
    let createdAt = $("#created-at");
    let tanggalKegiatan = $("#tanggal-kegiatan");
    let jenisKegiatan = $("#jenis-kegiatan");
    let tematik = $("#tematik");
    let alamatKegiatan = $("#alamat-kegiatan");
    let lampiran = $("#lampiran");
    let nomorPengajuan = $("#nomor-pengajuan");
    let rab = $("#rab");

    window.verifikasiPengajuanKegiatan = (input, el) => {
        lastTriggerButton = el;
        item = input;
        idPengajuan = item.id;

        let documentPengajuan = input.document.find(
            (item) => item.group == "document"
        );

        kelompokMasyarakat.text(
            item.user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat
                .kelompok_masyarakat
        );

        namaPic.text(item.user_akseslh.data_pic_kelompok_masyarakat.nama_pic);
        createdAt.text(
            new Date(item.created_at).toLocaleDateString("id-ID", {
                year: "numeric",
                month: "long",
                day: "numeric",
            })
        );

        tanggalKegiatan.text(
            new Date(item.tanggal_mulai_kegiatan).toLocaleDateString("id-ID", {
                year: "numeric",
                month: "long",
                day: "numeric",
            }) +
                " - " +
                new Date(item.tanggal_akhir_kegiatan).toLocaleDateString(
                    "id-ID",
                    {
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                    }
                )
        );

        jenisKegiatan.text(item.paket_kegiatan.jenis_kegiatan.jenis_kegiatan);
        tematik.text(
            item.paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan
                .tematik_kegiatan +
                " - " +
                item.paket_kegiatan.master_sub_tematik_kegiatan
                    .sub_tematik_kegiatan.sub_tematik_kegiatan
        );
        alamatKegiatan.text(item.alamat_kegiatan);

        nomorPengajuan.text(item.nomor_pengajuan);

        lampiran.html(
            '<i class="md md-insert-drive-file"></i> ' +
                "Proposal_" +
                item.nomor_pengajuan +
                ".pdf"
        );
        rab.html(
            '<i class="md md-insert-drive-file"></i> ' +
                "RAB_" +
                item.nomor_pengajuan +
                ".pdf"
        );

        $("#myModal").modal(
            {
                backdrop: "static", // klik di luar modal tidak menutup
                keyboard: false, // tekan ESC tidak menutup
            },
            "show"
        );

        $("#btn-buka-lampiran").on("click", function () {
            window.open(
                appRoute + "/storage/" + documentPengajuan.file_path,
                "_blank"
            );
        });

        $("#btn-buka-rab").on("click", function () {
            window.open(pengajuanKegiatanROute + "/" + idPengajuan, "_blank");
        });

        $(".btn-status-pengajuan").on("click", function () {
            $(".btn-status-pengajuan").attr("disabled", true);
            const formData = new FormData();

            const status = $(this).data("status");

            formData.append("_method", "PUT");
            formData.append("status", status);

            if (status == 0 || status == "0") {
                let catatan_log = $("#commentPengajuan").val();

                if (catatan_log.trim() === "") {
                    return alert("Comment tidak boleh kosong");
                }
                formData.append("catatan_log", catatan_log);
            }

            Swal.fire({
                title: "Konfirmasi",
                text:
                    "Anda yakin akan " +
                    (status == 1 ? "menyetujui" : "menolak") +
                    " pengajuan?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                reverseButtons: false,
                didOpen: () => {
                    // Pindahkan fokus ke tombol konfirmasi di dalam modal
                    document.querySelector(".swal2-confirm")?.focus();
                },
            })
                .then((response) => {
                    if (response.value) {
                        createData(
                            route + "/verifikasi-pengajuan/" + idPengajuan,
                            formData
                        )
                            .then((response) => {
                                if (response.data.code == 200) {
                                    Swal.fire({
                                        title: "Berhasil",
                                        text: response.data.message,
                                        icon: "success",
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            })
                            .catch((error) => {
                                let messages = error.response?.data?.message;

                                // Jika message berbentuk object (bisa jadi hasil dari validasi Laravel)
                                if (typeof messages === "object") {
                                    // Gabungkan semua pesan jadi satu string
                                    messages = Object.values(messages)
                                        .flat() // Gabungkan array dalam array
                                        .join("<br>"); // Pakai <br> biar toast bisa tampil multiline
                                }

                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    html: messages || "Terjadi kesalahan", // pakai html biar <br> berfungsi
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener(
                                            "mouseenter",
                                            Swal.stopTimer
                                        );
                                        toast.addEventListener(
                                            "mouseleave",
                                            Swal.resumeTimer
                                        );
                                    },
                                });
                            })
                            .finally(() => {
                                $(".btn-status-pengajuan").attr(
                                    "disabled",
                                    false
                                );
                            });
                    }
                })
                .finally(() => {
                    $(".btn-status-pengajuan").attr("disabled", false);
                });

            // return alert("success");
        });
    };

    window.verifikasiProfile = (input, el) => {
        lastTriggerButton = el;
        item = input;

        let profilePicRoute = $("#profile-pic-route").val();

        let id_data_pic = item.user_akseslh.data_pic_kelompok_masyarakat_id;
        let nama_pic = $("#nama_pic");
        let email_pic = $("#email_pic");
        let nomor_identitas_pic = $("#nomor_identitas_pic");
        let nomor_npwp_pic = $("#nomor_npwp_pic");
        let alamat_pic = $("#alamat_pic");
        let tempat_lahir = $("#tempat_lahir");
        let tanggal_lahir = $("#tanggal_lahir");
        let nohp_pic = $("#nohp_pic");
        let agama_id = $("#agama_id");
        let status_perkawinan_id = $("#status_perkawinan_id");
        let jenis_pekerjaan_id = $("#jenis_pekerjaan_id");
        let pendidikan_id = $("#pendidikan_id");
        let provinsi_pic = $("#provinsi_pic");
        let kabupaten_pic = $("#kabupaten_pic");
        let kecamatan_pic = $("#kecamatan_pic");
        let kelurahan_pic = $("#kelurahan_pic");
        let foto_ktp = item.user_akseslh.data_pic_kelompok_masyarakat.foto.find(
            (item) => item.group == "foto_ktp"
        );

        let profil_kelompok =
            item.user_akseslh.data_pic_kelompok_masyarakat.foto.find(
                (item) => item.group == "profil_kelompok"
            );

        provinsi_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.provinsi.name
        );

        kabupaten_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.kabupaten.name
        );

        kecamatan_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.kecamatan.name
        );

        kelurahan_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.kelurahan.name
        );

        agama_id.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.agama.agama
        );

        pendidikan_id.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.pendidikan.pendidikan
        );

        jenis_pekerjaan_id.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.jenis_pekerjaan
                .jenis_pekerjaan
        );

        status_perkawinan_id.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.status_perkawinan
                .status_pernikahan
        );

        nama_pic.val(item.user_akseslh.data_pic_kelompok_masyarakat.nama_pic);
        email_pic.val(item.user_akseslh.data_pic_kelompok_masyarakat.email_pic);
        nomor_identitas_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.nomor_identitas_pic
        );
        nomor_npwp_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.nomor_npwp_pic
        );
        alamat_pic.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.alamat_pic
        );
        tempat_lahir.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.tempat_lahir
        );
        tanggal_lahir.val(
            item.user_akseslh.data_pic_kelompok_masyarakat.tanggal_lahir
        );
        nohp_pic.val(item.user_akseslh.data_pic_kelompok_masyarakat.nohp_pic);

        $("#profileModal").modal(
            {
                backdrop: "static", // klik di luar modal tidak menutup
                keyboard: false, // tekan ESC tidak menutup
            },
            "show"
        );

        $("#lihat-foto-ktp").on("click", function () {
            window.open(appRoute + "/storage/" + foto_ktp.file_path, "_blank");
        });

        $("#lihat-profil-kelompok").on("click", function () {
            window.open(
                appRoute + "/storage/" + profil_kelompok.file_path,
                "_blank"
            );
        });

        $("#btn-tolak-profil").on("click", function () {
            let catatan_log = $("#commentProfile").val();

            if (catatan_log.trim() === "") {
                alert("Comment tidak boleh kosong");
            } else {
                const formData = new FormData();

                formData.append("pengajuan_kegiatan_id", item.id);
                formData.append("catatan_log", catatan_log);
                formData.append("_method", "PUT");

                this.disabled = true;

                Swal.fire({
                    title: "Konfirmasi",
                    text: "Anda yakin akan menolak profil ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus",
                    cancelButtonText: "Tidak",
                    reverseButtons: false,
                    didOpen: () => {
                        // Pindahkan fokus ke tombol konfirmasi di dalam modal
                        document.querySelector(".swal2-confirm")?.focus();
                    },
                }).then((result) => {
                    if (result.value) {
                        createData(
                            profilePicRoute + "/tolak-profil/" + id_data_pic,
                            formData
                        )
                            .then((response) => {
                                if (response.data.code == 200) {
                                    Swal.fire({
                                        title: "Berhasil",
                                        text: response.data.message,
                                        icon: "success",
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            })
                            .catch((error) => {
                                let messages = error.response?.data?.message;

                                // Jika message berbentuk object (bisa jadi hasil dari validasi Laravel)
                                if (typeof messages === "object") {
                                    // Gabungkan semua pesan jadi satu string
                                    messages = Object.values(messages)
                                        .flat() // Gabungkan array dalam array
                                        .join("<br>"); // Pakai <br> biar toast bisa tampil multiline
                                }

                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    html: messages || "Terjadi kesalahan", // pakai html biar <br> berfungsi
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener(
                                            "mouseenter",
                                            Swal.stopTimer
                                        );
                                        toast.addEventListener(
                                            "mouseleave",
                                            Swal.resumeTimer
                                        );
                                    },
                                });
                            })
                            .finally(() => {
                                this.disabled = false;
                            });
                    } else {
                        this.disabled = false;
                    }
                });
            }
        });
    };

    // Kembalikan fokus ke tombol pemicu setelah modal tertutup
    $("#myModal").on("hidden.bs.modal", function () {
        if (lastTriggerButton) {
            lastTriggerButton.focus();
        }
    });

    $("#profileModal").on("hidden.bs.modal", function () {
        if (lastTriggerButton) {
            lastTriggerButton.focus();
        }
    });

    window.tutupModal = () => {
        lastTriggerButton = null;
        item = null;
        $("#myModal").modal("hide");
        $("#profileModal").modal("hide");
    };
});
