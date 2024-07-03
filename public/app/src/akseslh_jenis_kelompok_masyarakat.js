"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var data_jenis_kegiatan = (function () {
    var initTable1 = function () {
        var table = $("#dt_jenis_kelompok_masyarakat");

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            ajax: "/akseslh/data-jenis-kelompok-masyarakat",
            columns: [
                { data: "DT_RowIndex" },
                { data: "jenis_kelompok_masyarakat" },
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
                        <a href="/akseslh/jenis-kelompok-masyarakat/` +
                            full.id +
                            `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deleteJenisKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_jenis_kelompok_masyarakat.init();
});

window.deleteJenisKelompokMasyarakat = (input) => {
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
                "/akseslh/jenis-kelompok-masyarakat/" + $(input).attr("data-id")
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
