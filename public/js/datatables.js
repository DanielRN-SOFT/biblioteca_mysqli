
// Datable de todas las tablas 

$(document).ready(function () {
  $("#tblGeneral").DataTable({
    destroy: true,
    responsive: true,
    fixedHeader: true,
    scrollY: 300,
    columnControl: ["order", "colVisDropdown"],
    ordering: {
      indicators: false,
      handler: false,
    },
    searching: false,
    language: {

      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
    },
  });
});



