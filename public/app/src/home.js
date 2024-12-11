let url = $("#url").val();
$(document).ready(function () {
    getAjax("user", "counter-users");
    getAjax("pic", "counter-pic");
    getAjax("kelompok", "counter-kelompok");
    getAjax("pengajuan", "counter-pengajuan");
});

function getAjax(flag, id) {
    $.ajax({
        url: url + "/dashboard?flag=" + flag, // URL endpoint yang disediakan oleh Laravel
        method: "GET", // Metode request GET
        dataType: "json", // Mengharapkan response dalam format JSON
        success: function (data) {
            // Jika request sukses, perbarui nilai counter dengan data total_users
            updateCounter(data.total, id);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching user count:", error); // Menangani error
        },
    });
}

function updateCounter(newValue, id) {
    // Temukan elemen dengan id 'counter-users'
    const counterElement = document.getElementById(id);

    // Perbarui nilai counter dengan nilai baru
    if (counterElement) {
        counterElement.textContent = newValue;
    }
}
