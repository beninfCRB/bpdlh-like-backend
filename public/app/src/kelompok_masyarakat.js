"use strict";

import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#kelompok-masyarakat-route").val();

var data_kelompok_masyarakat = (function () {
    var initTable1 = function () {
        var table = $("#dt_kelompok_masyarakat");
        var url_table = $("#data-table-kelompok-masyarakat").val();

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
                { data: "kelompok_masyarakat" },
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
                        if (full.jenis === null) {
                            return "-";
                        } else {
                            return full.jenis.jenis_kelompok_masyarakat;
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
    data_kelompok_masyarakat.init();
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
    const provinsi = $("#provinsi_kelompok_masyarakat_id").val();
    const app_url = $("#app_url").val();

    const old_kabupaten = $("#kabupaten_kelompok_masyarakat_id_old");
    $("#kabupaten_kelompok_masyarakat_id")
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
                $("#kabupaten_kelompok_masyarakat_id")
                    .append(append)
                    .trigger("change");
            }
        });
    }
};

window.getKecamatan = () => {
    const kabupaten = $("#kabupaten_kelompok_masyarakat_id").val();
    const app_url = $("#app_url").val();

    $("#kecamatan_kelompok_masyarakat_id")
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
                $("#kecamatan_kelompok_masyarakat_id")
                    .append(append)
                    .trigger("change");
            }
        });
    }
};

window.getKelurahan = () => {
    const kecamatan = $("#kecamatan_kelompok_masyarakat_id").val();
    const app_url = $("#app_url").val();
    $("#kelurahan_kelompok_masyarakat_id")
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
                $("#kelurahan_kelompok_masyarakat_id")
                    .append(append)
                    .trigger("change");
            }
        });
    }
};
