"use strict";

const numberFormat = new Intl.NumberFormat("id-ID");

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#log-jadwal-pembukaan-route").val();

var data_log_jadwal_pembukaan = (function () {
    var initTable1 = function () {
        var table = $("#dt_log_jadwal_pembukaan");
        var url_table = $("#data-table-log-jadwal-pembukaan").val();

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
                { data: "tanggal_awal" },
                { data: "jam_awal" },
                { data: "tanggal_akhir" },
                { data: "jam_akhir" },
                { data: "batch" },
                { data: "batas_pengajuan" },
                { data: "deleted_at" },
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
                    targets: -5,
                    render: function (data, type, full, meta) {
                        return numberFormat.format(full.batas_pengajuan);
                        // return full.batas_pengajuan;
                    },
                },
                {
                    targets: -4,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.deleted_at === null) {
                            return null;
                        } else {
                            return dayjs(full.deleted_at).format("DD MMM YYYY");
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
                        const userRole = $("#user-role").val();
                        var editRoute = route + "/" + full.id + "/edit";

                        if (userRole == "administrator") {
                            return (
                                `
                            <a href="` +
                                editRoute +
                                `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                              <i class="fa fa-pencil"></i>
                            </a>`
                            );
                        } else {
                            return ``;
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
    data_log_jadwal_pembukaan.init();
});

window.deleteJenisKegiatan = (input) => {
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

window.formatMoney = (input) => {
    const formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    });

    // Hapus semua karakter yang bukan angka
    let value = input.value.replace(/[^0-9]/g, "");

    // Format angka dengan `Intl.NumberFormat`
    if (value) {
        input.value = formatter.format(value);
    } else {
        input.value = "";
    }
};
