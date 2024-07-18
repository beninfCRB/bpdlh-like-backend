"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var data_master_komponen_rab = (function () {
    var initTable1 = function () {
        var table = $("#dt_master_komponen_rab");
        var url_table = $("#data-table-master-komponen-rab").val();
    
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
                {},
                {},
                { data: "komponen_rab"},
                { data: "standar_harga_unit"},
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
                        if (full.jenis_komponen === null) {
                            return "-";
                        } else {
                            return full.jenis_komponen.jenis_komponen_rab;
                        }
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        if (full.satuan === null) {
                            return "-";
                        } else {
                            return full.satuan.satuan;
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
                    targets: 6,
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
                        <a href="/akseslh/master-komponen-rab/` +
                            full.id +
                            `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteMasterKomponenRab(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_master_komponen_rab.init();
});

window.deleteMasterKomponenRab = (input) => {
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
                "/akseslh/master-komponen-rab/" + $(input).attr("data-id")
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
