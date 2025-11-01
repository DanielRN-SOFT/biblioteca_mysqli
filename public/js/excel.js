const btnGenerarExcel = document.querySelector("#btnGenerarExcel");
btnGenerarExcel.addEventListener("click", (e) => {
  e.preventDefault();

  const form = document.querySelector("#frmReportes");
  const tipoInforme = form.querySelector("#tipoInforme").value.trim();
  const fechaInicio = form.querySelector("#fechaInicio").value.trim();
  const fechaFin = form.querySelector("#fechaFin").value.trim();

  // Validar campos vacíos
  if (!tipoInforme || !fechaInicio || !fechaFin) {
    Swal.fire({
      title: "Campos incompletos",
      text: "Por favor, completa todos los campos antes de generar el reporte.",
      icon: "warning",
      confirmButtonText: "Aceptar",
    });
    return;
  }

  // Definir destino del formulario dinámicamente
  definirAccionFormulario();
  // Enviar el formulario al PHP correcto
  form.submit();
});

// ==========================
// FUNCIONES AUXILIARES
// ==========================

function actualizarTipoInforme() {
  const categoria = document.getElementById("tipoInforme").value;
  const tipoInforme = document.getElementById("tipoInformeCategoria");
  //LIMPIAR TODAS LAS OPCIONES ANTES DE AGREGAR NUEVAS
  tipoInforme.innerHTML = "";
   // Agregar opción por defecto SIEMPRE
  let opcionDefecto = document.createElement("option");
  opcionDefecto.value = "";
  opcionDefecto.textContent = "Seleccione un informe...";
  opcionDefecto.disabled = true; // No se puede seleccionar como válida
  opcionDefecto.selected = true; // Aparece seleccionada por defecto
  tipoInforme.appendChild(opcionDefecto);
  let opciones = [];

  if (categoria === "usuarios") {
    opciones = ["Usuarios con mas prestamos", "Usuarios con mas reservas"];
  } else if (categoria === "inventario") {
    opciones = [
      "Libros Disponibles",
      "Libros Prestados",
      "Libros Reservados",
      "Libros no Disponibles",
    ];
  } else if (categoria === "reservas") {
    opciones = [
      "Reservas Aprobadas",
      "Reservas Rechazadas",
      "Reservas Pendientes",
      "Reservas Canceladas",
    ];
  } else if (categoria === "prestamos") {
    opciones = [
      "Prestamos Activo",
      "Prestamos Devuelto",
      "Libros mas Prestados",
    ];
  }

  opciones.forEach((opcion) => {
    const opt = document.createElement("option");
    opt.value = opcion;
    opt.textContent = opcion;
    tipoInforme.appendChild(opt);
  });
}

function definirAccionFormulario() {
  const categoria = document.getElementById("tipoInforme").value;
  const tipoInforme = document.getElementById("tipoInformeCategoria").value;
  const form = document.getElementById("frmReportes");

  // SUBREPORTES DE USUARIOS
  if (
    categoria === "usuarios" &&
    (tipoInforme === "Usuarios con mas prestamos" ||
      tipoInforme === "Usuarios con mas reservas")
  ) {
    form.action = "../../controllers/generar_excel_subreportes.php";

    // SUBREPORTES DE INVENTARIO
  } else if (
    categoria === "inventario" &&
    (tipoInforme === "Libros Disponibles" ||
      tipoInforme === "Libros Prestados" ||
      tipoInforme === "Libros Reservados" ||
      tipoInforme === "Libros no Disponibles")
  ) {
    form.action = "../../controllers/generar_excel_subreportes.php";

    //SUBREPORTES DE RESERVAS
  } else if (
    categoria === "reservas" &&
    (tipoInforme === "Reservas Aprobadas" ||
      tipoInforme === "Reservas Rechazadas" ||
      tipoInforme === "Reservas Pendientes" ||
      tipoInforme === "Reservas Canceladas")
  ) {
    form.action = "../../controllers/generar_excel_subreportes.php";

    //SUBREPORTES DE PRÉSTAMOS
  } else if (
    categoria === "prestamos" &&
    (tipoInforme === "Prestamos Activo" ||
      tipoInforme === "Prestamos Devuelto" ||
      tipoInforme === "Libros mas Prestados")
  ) {
    form.action = "../../controllers/generar_excel_subreportes.php";

    // CUALQUIER OTRO CASO: REPORTE GENERAL
  } else {
    form.action = "../../controllers/generar_excel.php";
  }
}
