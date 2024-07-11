"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var data_tematik_kegiatan = (function () {
    var initTable1 = function () {
        var table = $("#dt_tematik_kegiatan");
        var url_table = $("#data-table-tematik-kegiatan").val();

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
                { data: "icon_tematik" },
                { data: "tematik_kegiatan" },
                { data: "short_id" },
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
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.end_date === null) {
                            return null;
                        } else {
                            return dayjs(full.end_date).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: 5,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.end_date === null) {
                            return null;
                        } else {
                            return dayjs(full.end_date).format("DD MMM YYYY");
                        }
                    },
                },
                {
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `
                        <a href="/akseslh/tematik-kegiatan/` +
                            full.id +
                            `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteTematikKegiatan(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_tematik_kegiatan.init();
});

window.deleteTematikKegiatan = (input) => {
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
                "/akseslh/tematik-kegiatan/" + $(input).attr("data-id")
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
