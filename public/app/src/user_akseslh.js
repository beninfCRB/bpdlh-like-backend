"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#user-akseslh-route").val();
var user_jenis_kelompok_route = $("#master-user-jenis-kelompok-route").val();

var data_user_akseslh = (function () {
    var initTable1 = function () {
        var table = $("#dt_user_akseslh");
        var url_table = $("#data-table-user-akseslh").val();

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
                { data: "nama_pic" },
                { data: "email" },
                { data: "role_user" },
                {},
                { data: "status_user" },
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
                    targets: 4,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.master_user_jenis_kelompok) {
                            return full.master_user_jenis_kelompok.map(
                                (item) => {
                                    if (item.jenis_kelompok_masyarakat) {
                                        return `<span class="badge badge-info">${item.jenis_kelompok_masyarakat.jenis_kelompok_masyarakat}</span>`;
                                    }
                                }
                            );
                        }
                    },
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
                        var showUserJenisKelompokRoute =
                            user_jenis_kelompok_route + "/" + full.id;
                        if (full.role_user == "verifikator") {
                            return (
                                `
                                <a href="` +
                                showUserJenisKelompokRoute +
                                `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Tambah">
                              <i class="fa fa-plus-circle"></i>
                            </a>
                            <a href="` +
                                editRoute +
                                `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                              <i class="fa fa-pencil"></i>
                            </a>
                            <a data-id=` +
                                full.id +
                                ` href="#" onclick="deletePICKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
                              <i class="fa fa-trash"></i>
                            </a>`
                            );
                        } else {
                            return (
                                `
                            <a href="` +
                                editRoute +
                                `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                              <i class="fa fa-pencil"></i>
                            </a>
                            <a data-id=` +
                                full.id +
                                ` href="#" onclick="deletePICKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
                              <i class="fa fa-trash"></i>
                            </a>`
                            );
                        }
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
    data_user_akseslh.init();
});

window.deletePICKelompokMasyarakat = (input) => {
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
