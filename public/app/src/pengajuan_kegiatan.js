"use strict";

const numberFormat = new Intl.NumberFormat("id-ID");

import axios from "axios";
import { createData, updateData, deleteData, updatePutData } from "../api";
var route = $("#data-pengajuan-kegiatan-route").val();

jQuery(function () {
    // data_pengajuan_kegiatan.init();
    console.log(route);

    if ($("#longitude").length && $("#latitude").length) {
        const longitude = $("#longitude").val();
        const latitude = $("#latitude").val();
        const alamat_kegiatan_realisasi = $("#alamat_kegiatan_realisasi").val();

        const map = L.map("map").setView([latitude, longitude], 13);
        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup(alamat_kegiatan_realisasi)
            .openPopup();
    }
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
                "/akseslh/paket-kegiatan/" + $(input).attr("data-id"),
            ).then((res) => {
                Swal.fire("Sukses", "Data berhasil dihapus", "success");
                window.location.reload();
            });
        }
    });
};

window.exportPengajuanKegiatan = (input, evt) => {
    evt.preventDefault();

    let formData = new FormData();
    formData.append("tanggal_awal", getValue("tanggal_awal"));
    formData.append("tanggal_akhir", getValue("tanggal_akhir"));

    beforeLoadingAttr("#saveBtn");
    Swal.fire({
        title: "Konfirmasi Pengunduhan Dokumen",
        text: "Mulai Pengunduhan Dokumen ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Simpan",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            createData(route + "/export-excel-pengajuan", formData, {
                responseType: "blob",
            })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data]),
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "users.xlsx");
                    document.body.appendChild(link);
                    link.click();
                })
                .catch((err) => {
                    afterLoadingAttr("#saveBtn");
                    let error = err.response.data;
                    if (!error.success) {
                        toastr.error(error.message);
                    }
                });
        }
    });
};

window.showModal = (id) => {
    $("#" + id).modal({
        keyboard: true,
        show: true,
        backdrop: true,
    });
};

window.hideModal = (id) => {
    $(".save-button").attr("disabled", false);
    $("#" + id).modal("hide");
};

window.tolakDraft = (input, evt) => {
    evt.preventDefault();
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    input.disabled = true;
    let formData = new FormData();
    formData.append("catatan_log", getValue("catatan_log"));

    updateData(
        route + "/" + $(input).attr("data-id") + "/tolak-draft-pengajuan",
        formData,
    )
        .then((response) => {
            console.log(response.data.message);

            Swal.fire("Sukses", response.data.message, "success");
            hideModal("modalTolakDraft");
            window.location.reload();
        })
        .catch((err) => {
            input.disabled = false;
            let error = err.response.data;
            if (!error.success) {
                Toast.fire({
                    icon: "warning",
                    title: error.data.catatan_log[0],
                });
            }
        });
};

window.updateSPTJM = (input, evt) => {
    evt.preventDefault();
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    input.disabled = true;

    let formData = new FormData();
    formData.append("nomor_sptjm", getValue("nomor_sptjm"));

    updateData(
        route + "/" + $(input).attr("data-id") + "/update-sptjm",
        formData,
    )
        .then((response) => {
            console.log(response.data.message);

            Swal.fire("Sukses", response.data.message, "success");
            hideModal("modalUpdateSPTJM");
            window.location.reload();
        })
        .catch((err) => {
            input.disabled = false;
            let error = err.response.data;
            if (!error.success) {
                Toast.fire({
                    icon: "warning",
                    title: error.data.nomor_sptjm[0],
                });
            }
        });
};

$("#formKembalikan").on("submit", function (e) {
    e.preventDefault();
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    const formData = new FormData();

    formData.append("id_pengajuan_kegiatan", $("#id_pengajuan_kegiatan").val());
    formData.append("dokumen_pendukung", $("#dokumen_pendukung")[0].files[0]);

    createData(
        route +
            "/" +
            formData.get("id_pengajuan_kegiatan") +
            "/kembalikan-pengajuan",
        formData,
        {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        },
    )
        .then((response) => {
            console.log(response.data.message);

            Swal.fire("Sukses", response.data.message, "success");
            hideModal("modalKembalikan");
            window.location.reload();
        })
        .catch((err) => {
            console.log(err.response);

            let error = err.response.data;
            if (!error.success) {
                Toast.fire({
                    icon: "warning",
                    title: error.data.dokumen_pendukung[0],
                });
            }
        });
});
