"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#profile-pic-route").val();

var data_profile_pic = (function () {
    var initTable1 = function () {
        var table = $("#dt_profile_pic");
        var url_table = $("#data-table-profile-pic").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            ajax: url_table,
            columns: [
                { data: "DT_RowIndex" },
                {
                    data: "data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis.jenis_kelompok_masyarakat",
                },
                {
                    data: "data_pic_kelompok_masyarakat.kelompok_masyarakat.kelompok_masyarakat",
                },
                { data: "data_pic_kelompok_masyarakat.nama_pic" },
                { data: "data_pic_kelompok_masyarakat.email_pic" },
                {},
                {},
                {},
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: -3,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.created_at === null) {
                            return null;
                        } else {
                            return dayjs(full.created_at).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: -2,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.updated_at === null) {
                            return null;
                        } else {
                            return dayjs(full.updated_at).format("DD MMM YYYY");
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
                   <a href="` +
                            editRoute +
                            `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Verifikasi Profile PIC">
                      <i class="fa fa-pencil"></i>
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
    data_profile_pic.init();
});

window.deleteJenisKelompokMasyarakat = (input) => {
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

window.restoreJenisKelompokMasyarakat = (input) => {
    var restoreRoute = route + "/" + $(input).attr("data-id") + "/restore";
    Swal.fire({
        title: "Konfirmasi",
        text: "Anda yakin ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        reverseButtons: false,
    }).then((result) => {
        if (result.value) {
            updateData(restoreRoute).then((res) => {
                Swal.fire("Sukses", "Data berhasil aktifkan", "success");
                window.location.reload();
            });
        }
    });
};
