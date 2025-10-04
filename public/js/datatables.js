
// Funcion para ejecutar los datatables
function dataTables(IDtabla){
$(document).ready(function () {
  $(IDtabla).DataTable({
    responsive: true,
    fixedHeader: true,
    scrollY: 300,
    columnControl: ["order", "colVisDropdown"],
    ordering: {
      indicators: false,
      handler: false,
    },
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
    },
  });
});
}

// Tabla usuarios
dataTables("#tblUsuarios")

// Dashboard
dataTables("#tblDashboard");
