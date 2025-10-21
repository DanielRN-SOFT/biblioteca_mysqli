// BUSQUEDA Y FILTROS
let btnBuscar = document.querySelector("#buscarPrestamo");

btnBuscar.addEventListener("click", () => {
  Swal.fire({
    title: '<h2 class="text-primary fw-bolder">Buscar Préstamos</h2>',
    html: `
      <input type="text" id="busquedaPrestamo" class="swal2-input" placeholder="Buscar préstamo...">
      <div id="sugerencias" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-bordered" id="tablaPrestamos" style="margin-top:10px; font-size:14px;">
        <thead>
          <tr>
            <th>Prestamo</th>
            <th>Reserva</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `,
    width: 800,
    showConfirmButton: false,

    // 🔹 Se ejecuta cuando el modal está completamente renderizado
    didOpen: () => {
      const inputBusqueda = document.getElementById("busquedaPrestamo");
      const tablaBody = document.querySelector("#tablaPrestamos tbody");

      inputBusqueda.addEventListener("keyup", function () {
        const texto = this.value.trim();

        // Si hay menos de 2 caracteres, limpia la tabla
        if (texto.length < 2) {
          tablaBody.innerHTML = "";
          return;
        }

        buscarPrestamos(texto, tablaBody);
      });
    },
  });
});

// 🔹 Función para buscar préstamos
function buscarPrestamos(texto, tablaBody) {
  texto = texto.trim();

  $.ajax({
    url: "../../controllers/buscarPrestamo.php",
    type: "POST",
    data: { query: texto },
    dataType: "json", //jQuery interpreta automáticamente el JSON

    success: function (prestamos) {
      console.log("Datos recibidos:", prestamos);
      tablaBody.innerHTML = "";

      if (!Array.isArray(prestamos) || prestamos.length === 0) {
        tablaBody.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-muted">No se encontraron préstamos</td>
          </tr>`;
        return;
      }

      prestamos.forEach((prestamo) => {
        tablaBody.insertAdjacentHTML(
          "beforeend",
          `
          <tr>
            <td>${prestamo.id}</td>
            <td>${prestamo.id_reserva}</td>
            <td>${prestamo.fecha_prestamo}</td>
            <td>${prestamo.fecha_devolucion}</td>
            <td>${prestamo.estado}</td>
          </tr>
        `
        );
      });
    },
  });
}
// ACTUALIZAR PRESTAMO
document.querySelectorAll(".btn-actualizar-prestamo").forEach((boton) => { //el foreach recorre todos los botones con la clase .btn-actualizar-prestamo y los agrega a la variable boton
  boton.addEventListener("click", () => {
    let id = boton.dataset.id; //se obtiene el id de cada préstamo
    // Mostrar alerta de confirmación
    Swal.fire({
      title: "¿Actualizar préstamo?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, actualizar",
      cancelButtonText: "Cancelar",
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../controllers/actualizarPrestamo.php",
          type: "POST",
          data: { idPrestamo: id },
          dataType: "json",
          success: function (response) {
            Swal.fire({
              title: "Hecho",
              text: response.message,
              icon: response.success ? "success" : "error",
              timer: 1000,
              showConfirmButton: false,
            }).then(() => {
              location.reload();
            });
          },
        });
      }
    });
  });
});
