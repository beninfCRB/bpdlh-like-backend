/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./public/app/src/akseslh_jenis_kegiatan.js ***!
  \**************************************************/


var data_jenis_kegiatan = function () {
  var initTable1 = function initTable1() {
    var table = $("#dt_jenis_kegiatan");

    // begin first table
    table.DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
      },
      responsive: true,
      searchDelay: 500,
      processing: true,
      ajax: "/akseslh/data-jenis-kegiatan",
      columns: [{
        data: "DT_RowIndex"
      }, {
        data: "jenis_kegiatan"
      }, {
        data: "username"
      }, {}],
      columnDefs: [{
        targets: 0,
        searchable: false,
        orderable: false
      }, {
        targets: -1,
        orderable: false,
        render: function render(data, type, full, meta) {
          return "\n                        <a href=\"/approval/" + full.id + "\" class=\"btn btn-sm btn-clean btn-icon btn-icon-md\" title=\"detail\">\n                              <i class=\"fa fa-eye\"></i>\n                        </a>";
        }
      }]
    });
  };
  return {
    //main function to initiate the module
    init: function init() {
      initTable1();
    }
  };
}();
jQuery(document).ready(function () {
  data_jenis_kegiatan.init();
});
/******/ })()
;