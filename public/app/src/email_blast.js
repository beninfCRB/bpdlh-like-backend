"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#email-blast-route").val();

var data_email_blast = (function () {
    var initTable1 = function () {
        var table = $("#dt_email_blast");
        var url_table = $("#data-table-email-blast").val();

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
                { data: "email" },
                { data: "status" },
                {},
                {},
                {},
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
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.pengajuan === null) {
                            return null;
                        } else {
                            return full.pengajuan.nomor_pengajuan;
                        }
                    },
                },
                {
                    targets: 2,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.pengajuan === null) {
                            return null;
                        } else {
                            if (full.pengajuan.user_akseslh === null) {
                                return null;
                            } else {
                                return full.pengajuan.user_akseslh.email;
                            }
                        }
                    },
                },
                {
                    targets: -4,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        if (full.sent_at === null) {
                            return null;
                        } else {
                            return dayjs(full.sent_at).format("DD MMM YYYY");
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
                            ` href="#" onclick="deleteKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    data_email_blast.init();
});

window.deleteKelompokMasyarakat = (input) => {
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

window.getKotaKabupaten = () => {
    const provinsi = $("#provinsi_email_blast_id").val();
    const app_url = $("#app_url").val();

    const old_kabupaten = $("#kabupaten_email_blast_id_old");
    $("#kabupaten_email_blast_id")
        .empty()
        .append("<option class='form-control' value=''>- Pilih Data -</option>")
        .trigger("change");

    if (provinsi) {
        const url = app_url + "/provinsi/" + provinsi;
        axios.get(url).then((response) => {
            const d = response.data.data.kota;
            for (const key in d) {
                const append =
                    "<option class='form-control' value='" +
                    d[key].id +
                    "'>" +
                    d[key].name +
                    "</option>";
                $("#kabupaten_email_blast_id").append(append).trigger("change");
            }
        });
    }
};

window.getKecamatan = () => {
    const kabupaten = $("#kabupaten_email_blast_id").val();
    const app_url = $("#app_url").val();

    $("#kecamatan_email_blast_id")
        .empty()
        .append("<option class='form-control' value=''>- Pilih Data -</option>")
        .trigger("change");

    if (kabupaten) {
        const url = app_url + "/kota/" + kabupaten;
        axios.get(url).then((response) => {
            const d = response.data.data.kecamatan;
            for (const key in d) {
                const append =
                    "<option class='form-control' value='" +
                    d[key].id +
                    "'>" +
                    d[key].name +
                    "</option>";
                $("#kecamatan_email_blast_id").append(append).trigger("change");
            }
        });
    }
};

window.getKelurahan = () => {
    const kecamatan = $("#kecamatan_email_blast_id").val();
    const app_url = $("#app_url").val();
    $("#kelurahan_email_blast_id")
        .empty()
        .append("<option class='form-control' value=''>- Pilih Data -</option>")
        .trigger("change");

    if (kecamatan) {
        const url = app_url + "/kecamatan/" + kecamatan;
        axios.get(url).then((response) => {
            const d = response.data.data.kelurahan;
            for (const key in d) {
                const append =
                    "<option class='form-control' value='" +
                    d[key].id +
                    "'>" +
                    d[key].name +
                    "</option>";
                $("#kelurahan_email_blast_id").append(append).trigger("change");
            }
        });
    }
};
