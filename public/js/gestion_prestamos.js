function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

//  Funcion para ver el detalle del prestamo
async function verDetalle(IDprestamo, IDreserva, estadoBD, tipoUsuarioBD) {
  cargandoAlerta("Cargando detalle...");
  const formData = new FormData();
  formData.append("IDprestamo", IDprestamo);

  const response = await fetch("../../controllers/detalle_prestamo.php", {
    method: "POST",
    body: formData,
  });

  const resultado = await response.json();

  let arregloCategorias = [];

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
      resultado.categorias.forEach(cat => {
        if(item.id == cat.libro_id){
          arregloCategorias.push(cat.nombre_categoria);
        }
      })
      
      let categoriasString = arregloCategorias.join("-")
      arregloCategorias = [];
      tabla += `
                        <tr class="p-5">
                        <td>${item.id_reserva} </td>
                            <td>${item.titulo}</td>
                            <td>${item.autor}</td>
                            <td>${item.ISBN}</td>
                            <td>${categoriasString}</td>
                        </tr>
                    `;
    });
    

    tabla += `
                </tbody>
                </table>
                `;

    if (tipoUsuarioBD == "Administrador") {
      if (estadoBD == "Prestado") {
        tabla += `<div class = "mt-4">`;

        tabla += `
                <button class="btn btn-primary btn-actualizar-prestamo fw-bold mx-1" 
                onclick="registrarDevolucion(
                                  ${IDprestamo},
                                  ${IDreserva},
                                  '${estadoBD}',
                                  'Devolucion')">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    Registrar devolucion
                </button>

                 <button class="btn btn-warning btn-actualizar-prestamo fw-bold mx-1" onclick="registrarRenovacion(
                                ${IDprestamo} , 
                                ${IDreserva},
                                '${estadoBD}',
                                'Extension')">
                                    <i class="fa-solid fa-handshake"></i>
                                    Extender prestamo
                  </button>

               
              
          `;
      } 
      
      if (estadoBD == "Vencido") {
        tabla += `
           <button class="btn btn-primary btn-actualizar-prestamo fw-bold mx-1" 
                onclick="registrarDevolucion(
                                  ${IDprestamo},
                                  ${IDreserva},
                                  '${estadoBD}',
                                  'Devolucion')">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    Registrar devolucion
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
async function registrarDevolucion(IDprestamo, IDreserva, estado, opcion) {
  cargandoAlerta("Registrando devolucion...");
  const formData = new FormData();
  formData.append("IDprestamo", IDprestamo);
  formData.append("IDreserva", IDreserva);
  formData.append("estado", estado);
  formData.append("opcion", opcion);

  cargandoAlerta("Registrando devolucion...");
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
function registrarRenovacion(IDprestamo, IDreserva, estado, opcion) {
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
      cargandoAlerta("Extendiendo prestamo...");
      const formData = new FormData();
      formData.append("IDprestamo", IDprestamo);
      formData.append("IDreserva", IDreserva);
      formData.append("estado", estado);
      formData.append("fechaDevolucion", fechaDevolucion);
      formData.append("opcion", opcion);
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
