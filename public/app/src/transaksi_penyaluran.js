"use strict";

const numberFormat = new Intl.NumberFormat("id-ID");

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#jenis-transaksi-penyaluran").val();

var data_transaksi_penyaluran = (function () {
    var initTable1 = function () {
        var table = $("#dt_transaksi_penyaluran");
        var url_table = $("#data-table-transaksi-penyaluran").val();

        // begin first table
        table.DataTable({
            layout: {
                topStart: {
                    buttons: ["copy", "csv", "excel", "pdf", "print"],
                },
            },
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
                {},
                {},
                {},
                {},
                {},
                { data: "nomor_rekening" },
                { data: "nama_pemilik_rekening" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: true,
                },
                {
                    targets: 1,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.pengajuan_kegiatan === null) {
                            return null;
                        } else {
                            return full.pengajuan_kegiatan.nomor_pengajuan;
                        }
                    },
                },
                {
                    targets: 2,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return dayjs(full.pengajuan_kegiatan.created_at).format(
                            "DD MMM YYYY"
                        );
                    },
                },
                {
                    targets: 3,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return full.pengajuan_kegiatan.user_akseslh
                            .data_pic_kelompok_masyarakat.kelompok_masyarakat
                            .kelompok_masyarakat;
                    },
                },
                {
                    targets: 4,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return full.pengajuan_kegiatan.user_akseslh
                            .data_pic_kelompok_masyarakat.kelompok_masyarakat
                            .jenis.jenis_kelompok_masyarakat;
                    },
                },
                {
                    targets: 5,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return dayjs(full.tanggal_penyaluran).format(
                            "DD MMM YYYY"
                        );
                    },
                },
                {
                    targets: 6,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return numberFormat.format(full.nilai_penyaluran);
                    },
                },
                {
                    targets: 7,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        return full.master_data_bank.nama_bank;
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
    data_transaksi_penyaluran.init();
});
