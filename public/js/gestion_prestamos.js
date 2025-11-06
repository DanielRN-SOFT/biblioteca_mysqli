//  Funcion para ver el detalle del prestamo
async function verDetalle(IDprestamo, IDreserva, estadoBD, tipoUsuarioBD) {
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

    if (tipoUsuarioBD == "Administrador") {
      if (estadoBD == "Prestado" || estadoBD == "Vencido") {
        tabla += `<div class = "mt-4">`;

        tabla += `
                <button class="btn btn-primary btn-actualizar-prestamo fw-bold" 
                onclick="registrarDevolucion(
                                  ${IDprestamo},
                                  ${IDreserva},
                                  '${estadoBD}' )">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    Registrar devolucion
                </button>
              
          `;
      }

      if (estadoBD == "Devuelto") {
        tabla += `
                  <button class="btn btn-warning btn-actualizar-prestamo fw-bold" onclick="registrarRenovacion(
                                ${IDprestamo} , 
                                ${IDreserva},
                                '${estadoBD}')">
                                    <i class="fa-solid fa-handshake"></i>
                                    Extender prestamo
                  </button>
          `;
      }

    
    }

      tabla += `
        <button id="btn-cancelar" class="btn btn-danger mx-1 mt-2 mt-sm-0 fw-bold">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Volver al listado
        </button>
      </div>
      `;

    Swal.fire({
      title: `Detalle del prestamo <span class="fw-bold m-0"> #${IDprestamo}</span>
      <br> <h5 class = ""> Propietario: <span class='fw-bold'> ${resultado.detalle[0].nombre} ${resultado.detalle[0].apellido} </span> </h5>`,
      html: tabla,
      icon: "info",
      showConfirmButton: false,
      width: 1000,
      didOpen: () => {
        const popup = Swal.getPopup();
        popup
          .querySelector("#btn-cancelar")
          .addEventListener("click", () => Swal.close("cancelar"));
      },
    });
  }
}

// Registrar devolucion
async function registrarDevolucion(IDprestamo, IDreserva, estado) {
  const formData = new FormData();
  formData.append("IDprestamo", IDprestamo);
  formData.append("IDreserva", IDreserva);
  formData.append("estado", estado);
  const request = await fetch("../../controllers/actualizarPrestamo.php", {
    method: "POST",
    body: formData,
  });

  const response = await request.json();

  if (response.success) {
    Swal.fire({
      title: "¡Exito!",
      text: response.message,
      icon: "success",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => {
      location.reload();
    });
  } else {
    Swal.fire({
      title: "¡Error!",
      text: response.message,
      icon: "error",
      time: 2000,
      showConfirmButton: false,
    });
  }
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
      const fechaDevolucion = document.querySelector("#fechaDevolucion").value;
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
