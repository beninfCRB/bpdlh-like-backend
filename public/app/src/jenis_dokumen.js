"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#jenis-dokumen-route").val();
var app_url = $("#app_url").val();

var data_jenis_dokumen = (function () {
    var initTable1 = function () {
        var table = $("#dt_jenis_dokumen");
        var url_table = $("#data-table-jenis-dokumen").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            scrollX: true,
            searchDelay: 500,
            processing: true,
            ajax: url_table,
            columns: [
                { data: "DT_RowIndex" },
                {},
                { data: "jenis_dokumen" },
                {},
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
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.tahapan_pengajuan_kegiatan === null) {
                            return null;
                        } else {
                            return full.tahapan_pengajuan_kegiatan
                                .deskripsi_kegiatan;
                        }
                    },
                },
                {
                    targets: 3,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.document_file === null) {
                            return null;
                        } else {
                            let document_url =
                                app_url +
                                "/storage/" +
                                full.document_file.file_path;
                            return (
                                `
                       <a href="` +
                                document_url +
                                `" target="_blank">
                          ` +
                                document_url +
                                `
                        </a>`
                            );
                        }
                    },
                },
                {
                    targets: -3,
                    searchable: false,
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
                    targets: -2,
                    searchable: false,
                    orderable: true,
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
                    orderable: true,
                    render: function (data, type, full, meta) {
                        var editRoute = route + "/" + full.id + "/edit";

                        return (
                            `
                       <a href="` +
                            editRoute +
                            `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteDokumen(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus File">
                          <i class="fa fa-file"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteJenisDokumen(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
                          <i class="fa fa-trash"></i>
                        </a>
                        `
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
    data_jenis_dokumen.init();
});

window.deleteJenisDokumen = (input) => {
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

window.deleteDokumen = (input) => {
    var deleteRoute = app_url + "/dokumen-delete/" + $(input).attr("data-id");

    Swal.fire({
        title: "Konfirmasi Hapus Dokumen",
        text: "Anda yakin akan menghapus dokumen dari data ini ?",
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
