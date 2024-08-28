"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";

var data_pengajuan_kegiatan = (function () {
    var initTable1 = function () {
        var table = $("#dt_pengajuan_kegiatan");
        var url_table = $("#data-table-pengajuan-kegiatan").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            // fixedColumns: true,
            paging: true,
            // scrollCollapse: true,
            scrollX: true,
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
                {},
                {},
                {},
                {},
                {},
                {},
                { data: "nomor_pengajuan" },
                {},
                {},
                {},
                {},
                {},
                {},
                {},
                {},
                { data: "judul_pengajuan_kegiatan" },
                { data: "alamat_kegiatan" },
                {},
                {},
                { data: "proposal_kegiatan" },
                { data: "ruang_lingkup_kegiatan" },
                {},
                { data: "flag" },
                { data: "created_at" },
                { data: "updated_at" },
            ],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: 1,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.jenis
                                .jenis_kelompok_masyarakat === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.jenis
                                .jenis_kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: 2,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.kelompok_masyarakat ===
                            null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .kelompok_masyarakat.kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .nama_pic === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.nama_pic;
                        }
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .jenis_identitas_pic === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .jenis_identitas_pic;
                        }
                    },
                },
                {
                    targets: 5,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .nomor_identitas_pic === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat
                                .nomor_identitas_pic;
                        }
                    },
                },
                {
                    targets: 6,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .kelurahan === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.kelurahan.name;
                        }
                    },
                },
                {
                    targets: 7,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .kecamatan === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.kecamatan.name;
                        }
                    },
                },
                {
                    targets: 8,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .kabupaten === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.kabupaten.name;
                        }
                    },
                },
                {
                    targets: 9,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .provinsi === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.provinsi.name;
                        }
                    },
                },
                {
                    targets: 10,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .email_pic === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.email_pic;
                        }
                    },
                },
                {
                    targets: 11,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.user_akseslh.data_pic_kelompok_masyarakat
                                .nohp_pic === null
                        ) {
                            return "-";
                        } else {
                            return full.user_akseslh
                                .data_pic_kelompok_masyarakat.nohp_pic;
                        }
                    },
                },
                {
                    targets: 12,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.user_akseslh.status_user === null) {
                            return "-";
                        } else {
                            return full.user_akseslh.status_user;
                        }
                    },
                },
                {
                    targets: 13,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.user_akseslh.role_user === null) {
                            return "-";
                        } else {
                            return full.user_akseslh.role_user;
                        }
                    },
                },
                {
                    targets: 15,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.paket_kegiatan.master_sub_tematik_kegiatan
                                .tematik_kegiatan === null
                        ) {
                            return "-";
                        } else {
                            return full.paket_kegiatan
                                .master_sub_tematik_kegiatan.tematik_kegiatan
                                .tematik_kegiatan;
                        }
                    },
                },
                {
                    targets: 16,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (
                            full.paket_kegiatan.master_sub_tematik_kegiatan
                                .sub_tematik_kegiatan === null
                        ) {
                            return "-";
                        } else {
                            return full.paket_kegiatan
                                .master_sub_tematik_kegiatan
                                .sub_tematik_kegiatan.sub_tematik_kegiatan;
                        }
                    },
                },
                {
                    targets: 17,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.paket_kegiatan.jenis_kegiatan === null) {
                            return "-";
                        } else {
                            return full.paket_kegiatan.jenis_kegiatan
                                .jenis_kegiatan;
                        }
                    },
                },
                {
                    targets: 18,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.kelurahan === null) {
                            return "-";
                        } else {
                            return full.kelurahan.name;
                        }
                    },
                },
                {
                    targets: 19,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.kecamatan === null) {
                            return "-";
                        } else {
                            return full.kecamatan.name;
                        }
                    },
                },
                {
                    targets: 20,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.kabupaten === null) {
                            return "-";
                        } else {
                            return full.kabupaten.name;
                        }
                    },
                },
                {
                    targets: 21,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.provinsi === null) {
                            return "-";
                        } else {
                            return full.provinsi.name;
                        }
                    },
                },
                {
                    targets: 22,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.paket_kegiatan === null) {
                            return "-";
                        } else {
                            if (full.paket_kegiatan.jumlah_peserta < 50) {
                                return (
                                    full.paket_kegiatan.jumlah_peserta +
                                    " Hectare"
                                );
                            } else {
                                return (
                                    full.paket_kegiatan.jumlah_peserta +
                                    " Orang"
                                );
                            }
                        }
                    },
                },
                {
                    targets: 25,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            full.tanggal_mulai_kegiatan +
                            " s.d " +
                            full.tanggal_akhir_kegiatan
                        );
                    },
                },
                {
                    targets: 26,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            full.time_mulai_kegiatan +
                            " s.d " +
                            full.time_akhir_kegiatan
                        );
                    },
                },
                {
                    targets: 27,
                    orderable: false,
                    width: "30%",
                },
                {
                    targets: 28,
                    orderable: false,
                    width: "30%",
                },
                {
                    targets: 29,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.rab_pengajuan_paket_kegiatans === null) {
                            return 0;
                        } else {
                            let total = 0;
                            for (
                                let index = 0;
                                index <
                                full.rab_pengajuan_paket_kegiatans.length;
                                index++
                            ) {
                                total +=
                                    full.rab_pengajuan_paket_kegiatans[index]
                                        .harga_unit *
                                    full.rab_pengajuan_paket_kegiatans[index]
                                        .qty;
                            }
                            return total;
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
    data_pengajuan_kegiatan.init();
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
