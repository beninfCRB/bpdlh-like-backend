"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#paket-kegiatan-route").val();

var data_paket_kegiatan = (function () {
    var initTable1 = function () {
        var table = $("#dt_paket_kegiatan");
        var url_table = $("#data-table-paket-kegiatan").val();

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
                { data: "nama_paket_kegiatan" },
                { data: "deskripsi_paket_kegiatan" },
                { data: "quota_paket_kegiatan" },
                { data: "pagu_paket_kegiatan" },
                { data: "tahap_pencairan_paket_kegiatan" },
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
                        if (full.jenis_kegiatan === null) {
                            return "-";
                        } else {
                            return full.jenis_kegiatan.jenis_kegiatan;
                        }
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        if (full.master_sub_tematik_kegiatan === null) {
                            return "-";
                        } else {
                            return (
                                full.master_sub_tematik_kegiatan
                                    .tematik_kegiatan.tematik_kegiatan +
                                " - " +
                                full.master_sub_tematik_kegiatan
                                    .sub_tematik_kegiatan.sub_tematik_kegiatan
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

                        return (
                            `
                        <a href="` +
                            editRoute +
                            `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a data-id=` +
                            full.id +
                            ` href="#" onclick="deletePaketKegiatan(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_paket_kegiatan.init();
    // generateFormTahapSalur();
});

window.generateFormTahapSalur = () => {
    let jmlTahapSalur = $("#tahap_pencairan_paket_kegiatan").val();
    if (jmlTahapSalur >= 1 && jmlTahapSalur <= 5) {
        generateForm(jmlTahapSalur);
    } else {
        const containerDiv = $("#dynamicForm");
        const tbody = $("#dynamicForm-tbody");
        tbody.empty();
        containerDiv.addClass("hidden");
    }
};

window.generateForm = (input) => {
    const containerDiv = $("#dynamicForm");
    containerDiv.removeClass("hidden");
    const tbody = $("#dynamicForm-tbody");
    tbody.empty();

    for (let index = 1; index <= input; index++) {
        const fieldGroup =
            `
            <div class="form-group col-md-12">
                <label for="porsi_pencairan">Porsi Tahap Salur ke-` +
            index +
            ` <span class="text-danger">*</span></label>
                <input type="number" min=0 class="form-control" id="porsi_pencairan" name="porsi_pencairan"
                    value="">
            </div>
        `;
        const tr =
            `
            <tr>
                <td width='50%'>Tahapan Salur ke-` +
            index +
            `</td>
                <td width='50%'>
                    <div class="input-group">
                        <input type="number" id="example-input2-group1"
                            name="porsi_pencairan[` +
            index +
            `]" class="form-control" min="1" required>
                        <span class="input-group-addon">%</span>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(tr);
    }
};

window.countSum = (iteration) => {
    let standar_harga_unit = $.trim(
        $("#standar_harga_unit_" + iteration).text()
    );
    let int_standar_harga_unit = parseInt(standar_harga_unit, 10);
    let qty = $("#qty_" + iteration).val();
    $("#harga_unit_" + iteration).val(int_standar_harga_unit);
};

window.createPaketKegiatan = (input, evt) => {
    evt.preventDefault();

    let saveButton = document.getElementById("saveBtn");

    saveButton.addEventListener("click", function () {
        var formData = new FormData();

        formData.append("title_id", getValue("title_id"));

        alert("Halo dunia");
    });
};

window.deletePaketKegiatan = (input) => {
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
