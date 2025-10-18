const btnGenerarPDF = document.querySelector("#btnGenerarPDF");
btnGenerarPDF.addEventListener("click", async (e) => {
  e.preventDefault();

  // Datos del formulario
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


  // Si todo está correcto, cambiar la acción y enviar el formulario al controlador PHP
  form.action = "../../controllers/generar_pdf.php"; // Ruta a tu PHP
  form.submit();
});
