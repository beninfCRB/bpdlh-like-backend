"use strict";

jQuery(document).ready(function () {
    window.tolakPengajuanPerubahan = () => {
        let catatan = $("#catatan").val();
        if (catatan.trim() === "") {
            alert("Catatan tidak boleh kosong");
        }

        const checkboxes = document.querySelectorAll(".profile-field");
        const selected = [];

        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selected.push(checkbox.name); // atau checkbox.name jika mau ambil name
            }
        });

        console.log(selected); // Misalnya untuk debug
        return selected;
    };

    window.setujuiPengajuanPerubahan = () => {
        const checkboxes = document.querySelectorAll(".profile-field");
        const selected = [];

        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selected.push(checkbox.name); // atau checkbox.name jika mau ambil name
            }
        });

        const formData = new FormData();

        formData.append("profile_field", selected);

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

    $(document).on("click", "#ceklis_semua", function () {
        const source = this;

        const checkboxes = document.querySelectorAll(".profile-field");
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = source.checked;
        });
        console.log(checkboxes);
    });
});
