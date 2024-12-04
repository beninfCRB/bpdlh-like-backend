"use strict";

window.Swal = require("sweetalert2");
window.axios = require("axios");
window.toastr = require("toastr");
window.select2 = require("select2");
// window.summernote = require("summernote");
window.chartJs = require("chart.js");
window.dayjs = require("dayjs");

import "chart.js";
import "dayjs/locale/id";
import "summernote";

dayjs.locale("id");
// import "sweetalert2/src/sweetalert2.scss";

window.baseUrlAsset = "https://bpdlh-cms.dev.pinteraktif.id/storage/";

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

window.messages = (message, url) => {
    Swal.fire({
        title: "Berhasil",
        text: message,
        icon: "success",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oke",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {
            window.location.href = url;
        }
    });
};

window.beforeLoadingAttr = (el) => {
    $(el).addClass(
        "btn btn-brand kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    );
};

window.afterLoadingAttr = (el) => {
    $(el).removeClass(
        "kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
    );
};

window.isNumber = (evt) => {
    var charCode = evt.which ? evt.which : evt.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
};

Number.prototype.formatMoney = function (c, d, t) {
    var n = this;
    c = isNaN((c = Math.abs(c))) ? 2 : c;
    d = d === undefined ? "." : d;
    t = t === undefined ? "," : t;
    var s = n < 0 ? "-" : "",
        i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
        j = (j = i.length) > 3 ? j % 3 : 0;
    return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
        (c
            ? d +
              Math.abs(n - i)
                  .toFixed(c)
                  .slice(2)
            : "")
    );
};

window.getValue = (element) => {
    let el = document.getElementById(element);
    if (el != null) {
        return el.value;
    } else {
        return null;
    }
};

window.getRadioValue = (element) => {
    let radios = document.getElementsByName(element);
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            return radios[i].value;
            break;
        }
    }
};

var KTAvatarDemo = (function () {
    // Private functions
    var initDemos = function () {
        var avatar1 = new KTAvatar("kt_avatar");
    };
    return {
        // public functions
        init: function () {
            initDemos();
        },
    };
})();

// KTUtil.ready(function () {
//     KTAvatarDemo.init();
// });

var readURL = function (input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(".profile-pic").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
};

$(".file-upload").on("change", function () {
    readURL(this);
});

$(".upload-button").on("click", function () {
    $(".file-upload").click();
});

window.sendFiles = (file, el) => {
    var formData = new FormData();
    formData.append("file", file);
    axios
        .post("/upload/image", formData)
        .then((res) => {
            let image = $("<img>").attr(
                "src",
                baseUrlAsset + res.data.data.file_path
            );
            $(el).summernote("insertNode", image[0]);
        })
        .catch((err) => {
            console.log(err);
        });
};

// window.summernoteSetup = (element) => {
//     $(element).summernote({
//         insertTableMaxSize: {
//             col: 200,
//             row: 200,
//         },
//         height: 300,
//         tabDisable: false,
//         blockquoteBreakingLevel: 2,
//         popover: {
//             image: [
//                 [
//                     "image",
//                     ["resizeFull", "resizeHalf", "resizeQuarter", "resizeNone"],
//                 ],
//                 ["float", ["floatLeft", "floatRight", "floatNone"]],
//                 ["remove", ["removeMedia"]],
//             ],
//             link: [["link", ["linkDialogShow", "unlink"]]],
//             table: [
//                 [
//                     "add",
//                     ["addRowDown", "addRowUp", "addColLeft", "addColRight"],
//                 ],
//                 ["delete", ["deleteRow", "deleteCol", "deleteTable"]],
//             ],
//         },
//         toolbar: [
//             // [groupName, [list of button]]
//             ["style", ["bold", "italic", "underline", "clear"]],
//             ["font", ["strikethrough", "superscript", "subscript"]],
//             ["fontsize", ["fontsize"]],
//             ["color", ["color"]],
//             ["para", ["ul", "ol", "paragraph", "height"]],
//             ["insert", ["link", "picture", "video", "table", "hr"]],
//             ["fontname", ["fontname"]],
//             ["view", ["fullscreen", "help"]],
//         ],
//         callbacks: {
//             onImageUpload: function (image) {
//                 sendFiles(image[0], element);
//             },
//         },
//     });
// };

export default messages;
