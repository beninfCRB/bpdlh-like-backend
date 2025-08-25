"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var route = $("#laporan-akhir-kegiatan-route").val();

var data_laporan_kegiatan = (function () {
    let tableInstance = null;

    var initTable1 = function () {
        var table = $("#dt_edit_laporan_akhir_kegiatan");
        var url_table = $("#data-table-laporan-akhir-kegiatan").val();

        // begin first table
        tableInstance = table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: url_table,
            columns: [
                {
                    data: "checkbox",
                    name: "checkbox",
                    orderable: false,
                    searchable: false,
                },
                { data: "DT_RowIndex" },
                { data: "nomor_pengajuan" },
                {
                    data: "user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.kelompok_masyarakat",
                },
                {
                    data: "user_akseslh.data_pic_kelompok_masyarakat.nama_pic",
                },
                { data: "judul_pengajuan_kegiatan" },
                {}, // status (rendered)
                { data: "created_at" }, // rendered by dayjs
                { data: "updated_at" }, // rendered by dayjs
            ],
            columnDefs: [
                {
                    targets: 1,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: -3, // status
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full) {
                        return full.deleted_at === null
                            ? "Aktif"
                            : "Tidak Aktif";
                    },
                },
                {
                    targets: -2, // created_at
                    searchable: false,
                    orderable: false,
                    render: function (data) {
                        return data ? dayjs(data).format("DD MMM YYYY") : null;
                    },
                },
                {
                    targets: -1, // updated_at
                    searchable: false,
                    orderable: false,
                    render: function (data) {
                        return data ? dayjs(data).format("DD MMM YYYY") : null;
                    },
                },
            ],
            drawCallback: function () {
                // Rebind select-all checkbox state
                $("#select-all").prop("checked", false);
            },
        });
    };

    return {
        init: function () {
            initTable1();
        },
        reload: function () {
            if (tableInstance) {
                tableInstance.ajax.reload(null, false);
            }
        },
    };
})();

jQuery(document).ready(function () {
    data_laporan_kegiatan.init();

    // Select All checkbox
    $(document).on("click", "#select-all", function () {
        $('input[name="pengajuan_kegiatan_id[]"]').prop(
            "checked",
            this.checked
        );
    });

    // Ketika salah satu checkbox item di-klik
    $(document).on(
        "click",
        'input[name="pengajuan_kegiatan_id[]"]',
        function () {
            const total = $('input[name="pengajuan_kegiatan_id[]"]').length;
            const checked = $(
                'input[name="pengajuan_kegiatan_id[]"]:checked'
            ).length;

            $("#select-all").prop("checked", total === checked);
        }
    );

    // Handle form submit
    $("#upload-form").on("submit", function (e) {
        e.preventDefault();

        const submitButton = $("#submit-button");
        submitButton.prop("disabled", true);

        const selected = $('input[name="pengajuan_kegiatan_id[]"]:checked')
            .map(function () {
                return this.value;
            })
            .get();

        if (selected.length === 0) {
            alert("Pilih minimal satu kegiatan terlebih dahulu.");
            return;
        }

        const file = $("#file-input")[0].files[0];

        if (!file) {
            alert("Silakan pilih file terlebih dahulu.");
            return;
        }

        const formData = new FormData();
        formData.append("file", file);
        selected.forEach((id) =>
            formData.append("pengajuan_kegiatan_id[]", id)
        );

        createData(route, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
            .then((response) => {
                alert(response.data.message);
                $("#file-input").val("");
                $("#select-all").prop("checked", false);
                data_laporan_kegiatan.reload();
            })
            .catch((error) => {
                // alert(error.response.data.message);
                swal("Error", error.response.data.message, "error");
            })
            .finally(() => {
                submitButton.prop("disabled", false);
            });
    });
});
