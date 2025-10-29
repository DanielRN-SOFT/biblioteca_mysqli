// ACTUALIZAR PRESTAMO
// document.querySelectorAll(".btn-actualizar-prestamo").forEach((boton) => { //el foreach recorre todos los botones con la clase .btn-actualizar-prestamo y los agrega a la variable boton
//   boton.addEventListener("click", () => {
//     let id = boton.dataset.id; //se obtiene el id de cada préstamo
//     // Mostrar alerta de confirmación
//     Swal.fire({
//       title: "Devolucion de prestamo",
//       text: "¿Desea registrar la devolucion del prestamo: #",
//       icon: "warning",
//       showCancelButton: true,
//       confirmButtonText: "Sí, actualizar",
//       cancelButtonText: "Cancelar",
//       customClass: {
//         confirmButton: "btn btn-success",
//         cancelButton: "btn btn-danger",
//       },
//     }).then((result) => {
//       if (result.isConfirmed) {
//         $.ajax({
//           url: "../../controllers/actualizarPrestamo.php",
//           type: "POST",
//           data: { idPrestamo: id },
//           dataType: "json",
//           success: function (response) {
//             Swal.fire({
//               title: "Hecho",
//               text: response.message,
//               icon: response.success ? "success" : "error",
//               timer: 1000,
//               showConfirmButton: false,
//             }).then(() => {
//               location.reload();
//             });
//           },
//         });
//       }
//     });
//   });
// });

//  Funcion para ver el detalle del prestamo
async function verDetalle(IDprestamo) {
  const formData = new FormData();
  formData.append("IDprestamo", IDprestamo);
  const response = await fetch("../../controllers/detalle_prestamo.php", {
    method: "POST",
    body: formData,
  });

  const resultado = await response.json();

  if (resultado.success) {
    let tabla = `
                    <table class="table table-striped table-bordered" style="width:100%;text-align:left;">
                        <thead>
                            <tr>
                            <th> Reserva </th>
                                <th>Libro</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

    resultado.detalle.forEach((item) => {
      tabla += `
                        <tr class="p-5">
                        <td>${item.id_reserva} </td>
                            <td>${item.titulo}</td>
                            <td>${item.autor}</td>
                            <td>${item.ISBN}</td>
                            <td>${item.categoria}</td>
                        </tr>
                    `;
    });

    tabla += `
                </tbody>
                </table>
                `;

    Swal.fire({
      title: `Detalle del prestamo <span class="fw-bold m-0"> #${IDprestamo}</span>
      <br> <h5 class = ""> Propietario: <span class='fw-bold'> ${resultado.detalle[0].nombre} ${resultado.detalle[0].apellido} </span> </h5>`,
      html: tabla,
      icon: "info",
      confirmButtonText: "Cerrar",
      width: 800,
    });
  }
}

// Registrar devolucion
function registrarDevolucion(IDprestamo, IDreserva, estado) {
  Swal.fire({
    title: "<span class='fw-bold'>Devolucion de prestamo</span>",
    html: `¿Desea registrar la devolucion de este prestamo? 
    <br> <strong> Prestamo: </strong> # ${IDprestamo} 
    <br> <strong> Reserva: </strong> # ${IDreserva} 
    `,
    icon: "info",
    confirmButtonText: "Si, registrar devolucion",
    showCancelButton: true,
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDprestamo", IDprestamo);
      formData.append("IDreserva", IDreserva);
      formData.append("estado", estado);
      const request = await fetch("../../controllers/actualizarPrestamo.php", {
        method: "POST",
        body: formData,
      });

      const response = await request.json();

      if (!response.success) {
        Swal.showValidationMessage(response.message);
      }

      return response;
    },
  }).then((result) => {
    if (result.isConfirmed && result.value.success) {
      Swal.fire({
        title: "¡Exito!",
        text: result.value.message,
        icon: "success",
        timer: 2000,
        showConfirmButton: false,
      }).then(() => {
        location.reload();
      });
    }
  });
}

// Reanudar prestamo
function registrarRenovacion(IDprestamo, IDreserva, estado) {
  const hoy = new Date().toISOString().split("T")[0];
  Swal.fire({
    title: "<span class='fw-bold'>Renovacion de prestamo</span>",
    html: `¿Desea renovar este prestamo? 
    <br> <strong> Prestamo: </strong> # ${IDprestamo} 
    <br> <strong> Reserva: </strong> # ${IDreserva} 
    <br>
    <div class= "mb-3">
 <label for="fecha" class="fw-bold form-label">
        Nueva fecha de devolucion:
      </label>
      <input type="date" id="fechaDevolucion" class="swal2-input" min="${hoy}" value="${hoy}" style="width:auto;">
    </div>  
   
    `,
    icon: "info",
    confirmButtonText: "Si, registrar devolucion",
    showCancelButton: true,
    cancelButtonText: "No, cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    width: 700,
    preConfirm: async () => {
      const fechaDevolucion =
        document.querySelector("#fechaDevolucion").value;
      console.log(fechaDevolucion);
      const formData = new FormData();
      formData.append("IDprestamo", IDprestamo);
      formData.append("IDreserva", IDreserva);
      formData.append("estado", estado);
      formData.append("fechaDevolucion", fechaDevolucion);
      const request = await fetch("../../controllers/actualizarPrestamo.php", {
        method: "POST",
        body: formData,
      });

      const response = await request.json();

      if (!response.success) {
        Swal.showValidationMessage(response.message);
      }

      return response;
    },
  }).then((result) => {
    if (result.isConfirmed && result.value.success) {
      Swal.fire({
        title: "¡Exito!",
        text: result.value.message,
        icon: "success",
        timer: 2000,
        showConfirmButton: false,
      }).then(() => {
        location.reload();
      });
    }
  });
}
