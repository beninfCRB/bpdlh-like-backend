"use strict";

import Swal from "sweetalert2";
import { createData, updatePutData } from "../api.js";

jQuery(document).ready(function () {
    let route = $("#profile_pic_route").val();
    console.log(route);

    let data_pic_kelompok_masyarakat_id = $(
        "#data_pic_kelompok_masyarakat_id",
    ).val();

    let profile_pic_id = $("#profile_pic_id").val();

    $(document).on("click", "#btn-tolak", function () {
        this.disabled = true;
        $("#btn-terima").prop("disabled", true);

        let catatan = $("#catatan").val();

        // Catatan tidak boleh kosong jika ditolak
        if (catatan.trim() === "") {
            alert("Catatan tidak boleh kosong");
        }

        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("status", 0);
        formData.append("catatan", catatan);

        Swal.fire({
            title: "Konfirmasi Tolak Perubahan Profil",
            text: "Anda yakin akan menolak perubahan data ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            reverseButtons: false,
        }).then((result) => {
            if (result.value) {
                createData(
                    route + "/pengajuan-perubahan-profil/" + profile_pic_id,
                    formData,
                )
                    .then((res) => {
                        Swal.fire(
                            "Sukses",
                            "Perubahan data berhasil ditolak",
                            "success",
                        );
                        window.location.href = route;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.disabled = false;
                        $("#btn-terima").prop("disabled", false);
                    });
            }
        });

        // const checkboxes = document.querySelectorAll(".profile-field");
        // const selected = [];

        // checkboxes.forEach(function (checkbox) {
        //     if (checkbox.checked) {
        //         selected.push(checkbox.name); // atau checkbox.name jika mau ambil name
        //     }
        // });

        // console.log(selected); // Misalnya untuk debug
        // return selected;
    });

    $(document).on("click", "#btn-terima", function () {
        this.disabled = true;
        $("#btn-tolak").prop("disabled", true);
        const checkboxes = document.querySelectorAll(".profile-field");

        const selected = [];

        const formData = new FormData();

        formData.append("_method", "PUT");
        formData.append("status", 1);

        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selected.push(checkbox.name); // atau checkbox.name jika mau ambil name
                formData.append("profile_field[]", checkbox.name);
            }
        });

        if (selected.length < 1) {
            return alert("Silahkan pilih data terlebih dahulu");
        }

        Swal.fire({
            title: "Konfirmasi Perubahan Profil",
            text: "Anda yakin akan merubah data ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            reverseButtons: false,
        })
            .then((result) => {
                if (result.value) {
                    createData(
                        route + "/pengajuan-perubahan-profil/" + profile_pic_id,
                        formData,
                    )
                        .then((res) => {
                            Swal.fire(
                                "Sukses",
                                "Data berhasil diperbaharui",
                                "success",
                            );
                            window.location.href = route;
                        })
                        .catch((error) => {
                            console.log(error);
                            Swal.fire("Gagal", error.message, "error");
                        })
                        .finally(() => {
                            this.disabled = false;
                            $("#btn-tolak").prop("disabled", false);
                        });
                }
            })
            .finally(() => {
                this.disabled = false;
                $("#btn-tolak").prop("disabled", false);
            });
    });

    $(document).on("click", "#ceklis_semua", function () {
        const source = this;

        const checkboxes = document.querySelectorAll(".profile-field");
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = source.checked;
        });
    });
});
