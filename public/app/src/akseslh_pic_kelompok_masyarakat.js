"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var data_pic_kelompok_masyarakat = (function () {
    var initTable1 = function () {
        var table = $("#dt_pic_kelompok_masyarakat");

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            ajax: "/akseslh/data-pic-kelompok-masyarakat",
            columns: [
                { data: "DT_RowIndex" },
                {},
                {},
                { data: "nama_user_eksternal" },
                { data: "jenis_identitas_user_eksternal" },
                { data: "nomor_identitas_user_eksternal" },
                { data: "email_user_eksternal" },
                { data: "nomor_hp_user_eksternal" },
                { data: "created_at" },
                { data: "updated_at" },
                {},
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: 1,
                    render: function (data, type, full, meta) {
                        if (full.kelompok_masyarakat === null) {
                            return "-";
                        } else {
                            return full.kelompok_masyarakat.kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        if (full.kelompok_masyarakat === null) {
                            return "-";
                        } else {
                            if (full.kelompok_masyarakat.jenis === null) {
                                return "-";
                            } else {
                                return full.kelompok_masyarakat.jenis
                                    .jenis_kelompok_masyarakat;
                            }
                        }
                    },
                },
                {
                    targets: -3,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.end_date === null) {
                            return null;
                        } else {
                            return dayJs(full.end_date).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: -2,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.end_date === null) {
                            return null;
                        } else {
                            return dayJs(full.end_date).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `
                       <a href="/career/` +
                            full.id +
                            `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="/akseslh/pic-kelompok-masyarakat/` +
                            full.id +
                            `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deletePICKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_pic_kelompok_masyarakat.init();
});

window.deletePICKelompokMasyarakat = (input) => {
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
            deleteData(
                "/akseslh/pic-kelompok-masyarakat/" + $(input).attr("data-id")
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
