"use strict";

import Swal from "sweetalert2";
import { createData, getResult } from "../api";
import axios from "axios";

var route = $("#tolak-pengajuan-dan-profil-route").val();

var data_tolak_pengajuan_dan_profil = (function () {
    let tableInstance = null;

    var initTable1 = function () {
        var table = $("#dt_tolak_pengajuan_dan_profil");
        var url_table = $("#data-table-tolak-pengajuan-dan-profil").val();

        // begin first table
        tableInstance = table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: url_table,
            columns: [
                { data: "DT_RowIndex" },
                { data: "nomor_pengajuan" },
                {
                    data: "email_pic",
                },
                {
                    data: "status_penolakan",
                },
                { data: "catatan_penolakan" },
                { data: "status" },
                {}, // rendered by dayjs
                {}, // rendered by dayjs
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: -2, // created_at
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.created_at === null) {
                            return null;
                        } else {
                            return dayjs(full.created_at).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: -1, // updated_at
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.updated_at === null) {
                            return null;
                        } else {
                            return dayjs(full.updated_at).format("DD MMM YYYY");
                        }
                    },
                },
            ],
        });
    };

    return {
        init: function () {
            initTable1();
        },
        reload: function () {
            if (tableInstance) {
                tableInstance.ajax.reload(null, false);
            }
        },
    };
})();

jQuery(document).ready(function () {
    data_tolak_pengajuan_dan_profil.init();

    // Handle form submit
    $("#upload-form").on("submit", function (e) {
        e.preventDefault();

        const submitButton = $("#submit-button");
        submitButton.prop("disabled", true);

        const file = $("#file-input")[0].files[0];

        if (!file) {
            Swal.fire({
                title: "Error",
                text: "Silakan pilih file yang akan diunggah.",
                icon: "error",
            });
            submitButton.prop("disabled", false);
            return;
        }

        const formData = new FormData();
        formData.append("file", file);

        createData(route, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
            .then((response) => {
                Swal.fire({
                    title: "Success",
                    text: "Data berhasil diunggah.",
                    icon: "success",
                });
                $("#file-input").val("");
                data_tolak_pengajuan_dan_profil.reload();
            })
            .catch((error) => {
                Swal.fire({
                    title: "Error",
                    text:
                        error.response?.data?.message ||
                        "Terjadi kesalahan saat mengunggah data.",
                    icon: "error",
                });
            })
            .finally(() => {
                submitButton.prop("disabled", false);
            });
    });

    // handle mulai jobs
    $("#mulai-button").on("click", function () {
        this.disabled = true; // Disable the button to prevent multiple clicks
        Swal.fire({
            title: "Konfirmasi",
            text: "Anda yakin akan memulai proses penolakan ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Tidak",
            reverseButtons: false,
        })
            .then((result) => {
                if (result.value) {
                    this.disabled = true; // Disable the button to prevent multiple clicks
                    axios
                        .post(route + "/proses")
                        .then((res) => {
                            console.log(res.data.data);

                            Swal.fire(
                                "Sukses",
                                "Proses penolakan dimulai",
                                "success"
                            );
                            data_tolak_pengajuan_dan_profil.reload();
                        })
                        .catch((error) => {
                            Swal.fire(
                                "Error",
                                error.response?.data?.message ||
                                    "Terjadi kesalahan saat memulai proses.",
                                "error"
                            );
                        })
                        .finally(() => {
                            this.disabled = false; // Re-enable the button after the process
                        });
                }
            })
            .catch((error) => {
                alert(error);
            })
            .finally(() => {
                this.disabled = false; // Re-enable the button after the process
            });
    });
});
