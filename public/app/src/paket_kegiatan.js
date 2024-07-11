"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

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
                        if (full.tematik_kegiatan === null) {
                            return "-";
                        } else {
                            return full.tematik_kegiatan.tematik_kegiatan;
                        }
                    },
                },
                {
                    targets: 8,
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
                    targets: 9,
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
                       <a href="/career/` +
                            full.id +
                            `" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="/akseslh/paket-kegiatan/` +
                            full.id +
                            `/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Ubah">
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
});

window.generateFormTahapSalur = () => {
    let jmlTahapSalur = $("#tahap_pencairan_paket_kegiatan").val();
    if (jmlTahapSalur > 1 && jmlTahapSalur <= 5) {
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
                <td>Tahapan Salur ke-` +
            index +
            `</td>
                <td>
                    <div class="input-group">
                        <input type="number" id="example-input2-group1"
                            name="porsi_pencairan[` +
            index +
            `]" class="form-control" min="1"
                            max="5">
                        <span class="input-group-addon">%</span>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(tr);
    }
};

window.deletePaketKegiatan = (input) => {
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
                "/akseslh/paket-kegiatan/" + $(input).attr("data-id")
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};
