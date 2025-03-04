"use strict";

import axios from "axios";
import {
    createData,
    updateData,
    updatePutData,
    deleteData,
    showData,
} from "../api";
var route = $("#pic-kelompok-masyarakat-route").val();

var data_pic_kelompok_masyarakat = (function () {
    var initTable1 = function () {
        var table = $("#dt_pic_kelompok_masyarakat");
        var url_table = $("#data-table-pic-kelompok-masyarakat").val();

        // begin first table
        table.DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
            },
            responsive: true,
            searchDelay: 500,
            processing: true,
            scrollX: true,
            ajax: url_table,
            columns: [
                { data: "DT_RowIndex" }, //0
                {}, //1
                {}, //2
                { data: "nama_pic" }, //3
                { data: "jenis_identitas_pic" }, //4
                { data: "nomor_identitas_pic" }, //5
                { data: "nomor_npwp_pic" }, //6
                { data: "email_pic" }, //7
                {}, //8 Provinsi
                {}, //9 Kota
                {}, //10 Kecamatan
                {}, //11 Kelurahan
                { data: "alamat_pic" }, //12
                { data: "tempat_lahir" }, //13
                { data: "tanggal_lahir" }, //14
                {}, //15 Agama
                {}, //16 Status Perkawinan
                {}, //17 Jenis Pekerjaan
                {}, //18 Pendidikan
                { data: "nohp_pic" }, //19
                {}, //20
                { data: "created_at" }, //21
                { data: "updated_at" }, //22
                {}, //23
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
                        if (full.kelompok_masyarakat === null) {
                            return "-";
                        } else {
                            return full.kelompok_masyarakat.kelompok_masyarakat;
                        }
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        if (full.kelompok_masyarakat === null) {
                            return "-";
                        } else {
                            if (full.kelompok_masyarakat.jenis === null) {
                                return "-";
                            } else {
                                return full.kelompok_masyarakat.jenis
                                    .jenis_kelompok_masyarakat;
                            }
                        }
                    },
                },
                {
                    targets: 8,
                    render: function (data, type, full, meta) {
                        if (full.provinsi === null) {
                            return "-";
                        } else {
                            return full.provinsi.name;
                        }
                    },
                },
                {
                    targets: 9,
                    render: function (data, type, full, meta) {
                        if (full.kabupaten === null) {
                            return "-";
                        } else {
                            return full.kabupaten.name;
                        }
                    },
                },
                {
                    targets: 10,
                    render: function (data, type, full, meta) {
                        if (full.kecamatan === null) {
                            return "-";
                        } else {
                            return full.kecamatan.name;
                        }
                    },
                },
                {
                    targets: 11,
                    render: function (data, type, full, meta) {
                        if (full.kelurahan === null) {
                            return "-";
                        } else {
                            return full.kelurahan.name;
                        }
                    },
                },
                {
                    targets: 15,
                    render: function (data, type, full, meta) {
                        if (full.agama === null) {
                            return "-";
                        } else {
                            return full.agama.agama;
                        }
                    },
                },
                {
                    targets: 16,
                    render: function (data, type, full, meta) {
                        if (full.status_perkawinan === null) {
                            return "-";
                        } else {
                            return full.status_perkawinan.status_pernikahan;
                        }
                    },
                },
                {
                    targets: 17,
                    render: function (data, type, full, meta) {
                        if (full.jenis_pekerjaan === null) {
                            return "-";
                        } else {
                            return full.jenis_pekerjaan.jenis_pekerjaan;
                        }
                    },
                },
                {
                    targets: 18,
                    render: function (data, type, full, meta) {
                        if (full.pendidikan === null) {
                            return "-";
                        } else {
                            return full.pendidikan.pendidikan;
                        }
                    },
                },
                {
                    targets: -4,
                    searchable: true,
                    orderable: true,
                    render: function (data, type, full, meta) {
                        if (full.user_akseslh === null) {
                            return "-";
                        } else {
                            return full.user_akseslh.status_user;
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
                            ` href="#" onclick="deletePICKelompokMasyarakat(this,event)" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Hapus">
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
    // data_pic_kelompok_masyarakat.init();
});

window.deletePICKelompokMasyarakat = (input) => {
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
    const provinsi = $("#provinsi_pic").val();
    const app_url = $("#app_url").val();

    const old_kabupaten = $("#kabupaten_pic_old");
    $("#kabupaten_pic")
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
                $("#kabupaten_pic").append(append).trigger("change");
            }
        });
    }
};

window.getKecamatan = () => {
    const kabupaten = $("#kabupaten_pic").val();
    const app_url = $("#app_url").val();
    $("#kecamatan_pic")
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
                $("#kecamatan_pic").append(append).trigger("change");
            }
        });
    }
};

window.getKelurahan = () => {
    const kecamatan = $("#kecamatan_pic").val();
    const app_url = $("#app_url").val();
    $("#kelurahan_pic")
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
                $("#kelurahan_pic").append(append).trigger("change");
            }
        });
    }
};
