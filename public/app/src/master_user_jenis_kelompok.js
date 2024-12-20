"use strict";
import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#master-user-jenis-kelompok-route").val();

var data_master_user_jenis_kelompok = (function () {
    var initTable1 = function () {
        var table = $("#dt_master_user_jenis_kelompok");
        var url_table = $("#data-table-master-user-jenis-kelompok").val();
        var user_id = $("#user_id").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            ajax: url_table + "/" + user_id,
            columns: [{ data: "DT_RowIndex" }, {}, {}],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: 1,
                    render: function (data, type, full, meta) {
                        if (full.jenis_kelompok_masyarakat === null) {
                            return "-";
                        } else {
                            return full.jenis_kelompok_masyarakat
                                .jenis_kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var editRoute = route + "/" + full.id + "/edit";

                        return (
                            `
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteMasterUserJenisKelompok(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
                          <i class="fa fa-trash"></i>
                        </a>`
                        );
                    },
                },
            ],
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initTable1();
        },
    };
})();

jQuery(document).ready(function () {
    data_master_user_jenis_kelompok.init();
});

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

window.createMasterUserJenisKelompok = (input, evt) => {
    evt.preventDefault();

    var formData = new FormData();

    formData.append("user_akseslh_id", $("#user_id").val());
    formData.append(
        "jenis_kelompok_masyarakat_id",
        getValue("jenis_kelompok_masyarakat_id")
    );

    Swal.fire({
        title: "Konfirmasi Penyimpanan",
        text: "Apakah anda yakin menyimpan data ini ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Simpan",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            createData(route, formData)
                .then((res) => {
                    Swal.fire("Sukses", "Data berhasil disimpan", "success");
                    window.location.reload(); // Halaman akan di-reload
                })
                .catch((err) => {
                    let error = err.response.data;
                    if (!error.success) {
                        toastr.error(error.message);
                        Toast.fire({
                            icon: "error",
                            title: error.message,
                        });
                    }
                });
        }
    });
};

window.deleteMasterUserJenisKelompok = (input) => {
    var deleteRoute = route + "/" + $(input).attr("data-id");
    Swal.fire({
        title: "Konfirmasi Hapus",
        text: "Anda yakin akan menghapus data ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Tidak",
        reverseButtons: false,
    }).then((result) => {
        if (result.value) {
            deleteData(deleteRoute).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
