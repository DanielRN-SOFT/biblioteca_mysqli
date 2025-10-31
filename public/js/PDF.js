const btnGenerarPDF = document.querySelector("#btnGenerarPDF");
btnGenerarPDF.addEventListener("click", (e) => {
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

  if (categoria === "Usuario") {
    opciones = ["Usuarios con mas prestamos", "Usuarios con mas reservas"];
  } else if (categoria === "Inventario") {
    opciones = [
      "Libros Disponibles",
      "Libros Prestados",
      "Libros Reservados",
      "Libros no Disponibles",
    ];
  } else if (categoria === "Reserva") {
    opciones = [
      "Reservas Aprobadas",
      "Reservas Rechazadas",
      "Reservas Pendientes",
    ];
  } else if (categoria === "Prestamo") {
    opciones = [
      "Prestamos Vigente",
      "Prestamos Cancelado",
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
    categoria === "Usuario" &&
    (tipoInforme === "Usuarios con mas prestamos" ||
      tipoInforme === "Usuarios con mas reservas")
  ) {
    form.action = "../../controllers/generar_pdf_subreportes.php";

    // SUBREPORTES DE INVENTARIO
  } else if (
    categoria === "Inventario" &&
    (tipoInforme === "Libros Disponibles" ||
      tipoInforme === "Libros Prestados" ||
      tipoInforme === "Libros Reservados" ||
      tipoInforme === "Libros no Disponibles")
  ) {
    form.action = "../../controllers/generar_pdf_subreportes.php";

    //SUBREPORTES DE RESERVAS
  } else if (
    categoria === "Reserva" &&
    (tipoInforme === "Reservas Aprobadas" ||
      tipoInforme === "Reservas Rechazadas" ||
      tipoInforme === "Reservas Pendientes")
  ) {
    form.action = "../../controllers/generar_pdf_subreportes.php";

    //SUBREPORTES DE PRÉSTAMOS
  } else if (
    categoria === "Prestamo" &&
    (tipoInforme === "Prestamos Vigente" ||
      tipoInforme === "Prestamos Cancelado" ||
      tipoInforme === "Libros mas Prestados")
  ) {
    form.action = "../../controllers/generar_pdf_subreportes.php";

    // CUALQUIER OTRO CASO: REPORTE GENERAL
  } else {
    form.action = "../../controllers/generar_pdf.php";
  }
}
