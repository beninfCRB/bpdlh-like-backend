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
                        if (full.pengajuan_kegiatan.user_akseslh === null) {
                            return "-";
                        } else {
                            return full.pengajuan_kegiatan.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: 4,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.pengajuan_kegiatan.user_akseslh === null) {
                            return "-";
                        } else {
                            return full.pengajuan_kegiatan.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.jenis
                                .jenis_kelompok_masyarakat;
                        }
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
            initComplete: function () {
                // Hitung total nilai_penyaluran
                calculateTotal();
            },
            // Jika menggunakan ajax, tambahkan callback untuk menghitung total setelah data dimuat
            ajax: {
                url: url_table,
                dataSrc: function (json) {
                    calculateTotal(json.data); // Panggil fungsi menghitung total
                    return json.data;
                },
            },
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initTable1();
            let total = $("#total-transaksi-pencairan").text("1000000");
        },
    };
})();

jQuery(document).ready(function () {
    data_transaksi_penyaluran.init();
});

// Fungsi untuk menghitung total nilai_penyaluran
function calculateTotal(data) {
    var total = 0;
    // Jika data diberikan, hitung total dari data tersebut
    if (data) {
        data.forEach(function (item) {
            total += parseFloat(item.nilai_penyaluran) || 0; // Pastikan nilai diubah ke float
        });
    } else {
        // Jika tidak, hitung total dari baris yang ada di DataTable
        var table = $("#dt_transaksi_penyaluran").DataTable();
        // Menggunakan API DataTables untuk mendapatkan data dari semua baris
        var rows = table.rows().data();
        rows.each(function (item) {
            total += parseFloat(item.nilai_penyaluran) || 0; // Pastikan nilai diubah ke float
        });
    }
    // Perbarui nilai total di footer
    $("#total-transaksi-pencairan").text(total.toLocaleString()); // Format total jika diperlukan
}
