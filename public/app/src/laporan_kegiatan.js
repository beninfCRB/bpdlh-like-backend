"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var route = $("#laporan-kegiatan-route").val();

var data_laporan_kegiatan = (function () {
    var initTable1 = function () {
        var table = $("#dt_laporan_kegiatan");
        var url_table = $("#data-table-laporan-kegiatan").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            ajax: url_table,
            serverSide: true,
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false,
                },
                { data: "DT_RowIndex" },
                { data: "nomor_pengajuan" },
                { data: "user_akseslh_id" },
                { data: "judul_pengajuan_kegiatan" },
                {},
                { data: "created_at" },
                { data: "updated_at" },
            ],
            columnDefs: [
                {
                    targets: 1,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: -3,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.deleted_at === null) {
                            return "Aktif";
                        } else {
                            return "Tidak Aktif";
                        }
                    },
                },
                {
                    targets: -2,
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
                    targets: -1,
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
    data_laporan_kegiatan.init();
});

// Select All checkbox
$("#select-all").on("click", function () {
    $('input[name="selected_id[]"]').prop("checked", this.checked);
});

// Prevent submit if no rows selected
$("#upload-form").on("submit", function (e) {
    if ($('input[name="selected_id[]"]:checked').length === 0) {
        e.preventDefault();
        alert("Pilih minimal satu kegiatan terlebih dahulu.");
    }
});
