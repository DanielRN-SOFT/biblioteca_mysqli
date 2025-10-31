async function crearReserva(IDcliente, tipoUsuarioBD) {
  Swal.fire({
    title: '<span class="text-success fw-bold">Crear reserva</span>',
    html: `
      <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar libro..." onkeyup="buscarProducto(this.value)">
      <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-striped table-bordered" style="width:100%;text-align:left; margin-top:10px;" id="tablaProductos">
        <thead>
          <tr> 
            <th>Título</th>
            <th>Autor</th>
            <th>Categoría</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody id="t-body"></tbody>
      </table>
    `,
    width: 800,
    showCancelButton: true,
    confirmButtonText: "Guardar reserva",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },

    preConfirm: async () => {
      try {
        // Recolectar los libros seleccionados
        const productos = [];
        document.querySelectorAll("#tablaProductos tbody tr").forEach((row) => {
          const IDlibro = parseInt(row.dataset.id);
          if (IDlibro > 0) {
            productos.push(IDlibro);
          }
        });

        if (productos.length === 0) {
          Swal.showValidationMessage(
            "Agregue al menos un libro para completar la reserva"
          );
          return false;
        }

        if (tipoUsuarioBD === "Cliente") {
          // Validar límite o condiciones en el servidor
          const validar = await fetch("../../controllers/contar_reservas.php", {
            method: "POST",
            body: new URLSearchParams({
              IDcliente: IDcliente,
              librosSeleccionados: JSON.stringify(productos),
            }),
          });
          const resultado = await validar.json();

          if (!resultado.success) {
            Swal.showValidationMessage(resultado.message);
            return false; // Detiene el flujo
          }
        }

        // Enviar datos al servidor para guardar la reserva
        const respuesta = await fetch("../../controllers/agregar_reserva.php", {
          method: "POST",
          body: new URLSearchParams({
            libros: JSON.stringify(productos),
            IDcliente: IDcliente,
          }),
        });

        const res = await respuesta.json();

        if (!res.success) {
          Swal.showValidationMessage(res.message);
          return false;
        }

        // Retornar mensaje de éxito a SweetAlert
        return res.message;
      } catch (error) {
        Swal.showValidationMessage("Error al procesar la reserva." + error);
        console.error(error);
        return false;
      }
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value) {
      Swal.fire("¡Éxito!", resultado.value, "success").then(() => {
        location.reload();
      });
    }
  });
}

function buscarProducto(texto) {
  if (texto.length < 2) {
    document.getElementById("sugerencias").innerHTML = "";
    return;
  }

  let tablaBody = document.querySelector("#t-body");

  $.ajax({
    url: "../../controllers/buscar_libros_reserva.php",
    type: "POST",
    data: { query: texto },
    success: function (response) {
      const libros = JSON.parse(response);

      let html = `<ul class="list-group">`;

      libros.forEach((libro) => {
        html += `
            <li class = "list-group-item list-group-item-action"
              onclick = "agregarLibro('${libro.id}','${libro.titulo}', '${libro.autor}', '${libro.categoria}')">
                <strong> 📙 Titulo:  </strong>${libro.titulo} 
              - <strong> ✒️ Autor:  </strong> ${libro.autor} 
              - <strong> 📚 Categoria:  </strong> ${libro.categoria}
            </li>
        `;
      });

      html += "</ul>";
      document.getElementById("sugerencias").innerHTML = html;
    },
  });
}

// Agregar producto a la tabla
function agregarLibro(id, titulo, autor, categoria) {
  const tabla = document.querySelector("#tablaProductos tbody");

  if ([...tabla.querySelectorAll("tr")].some((row) => row.dataset.id === id))
    return;

  const fila = document.createElement("tr");
  fila.dataset.id = id;

  fila.innerHTML = `
    <td> ${titulo} </td>
    <td> ${autor} </td>
    <td> ${categoria} </td>
     <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();">Quitar</button></td>

  `;

  tabla.appendChild(fila);
  document.getElementById("sugerencias").innerHTML = "";
  document.getElementById("busquedaProducto").value = "";
}

// Cancelar reserva
function cancelarReserva(IDreservaBD, estadoBD) {
  console.log(estadoBD);
  Swal.fire({
    title: '<span class="text-danger mb-3 fw-bold"> Cancelar reserva </span>',
    html: `¿Esta seguro de cancelar esta reserva?: <br>
    <strong>No. de reserva: </strong> ${IDreservaBD}
    `,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, cancelar reserva",
    cancelButtonText: "No, volver al listado",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    width: 700,
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDreserva", IDreservaBD);
      formData.append("estado", estadoBD);

      const response = await fetch(
        "../../controllers/eliminar_integrar_reserva.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const respuesta = await response.json();

      if (!respuesta.success) {
        Swal.showValidationMessage(respuesta.message);
      }

      return respuesta;
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}

// Reintegrar reserva
function reintegrarReserva(IDreservaBD, estadoBD) {
  Swal.fire({
    title: '<span class="text-success mb-3 fw-bold"> Reactivar reserva </span>',
    html: `¿Esta seguro de volver a activar esta reserva?: <br>
    <strong>No. de reserva: </strong> ${IDreservaBD}
    `,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, reactivar reserva",
    cancelButtonText: "No, volver al listado",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    width: 700,
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDreserva", IDreservaBD);
      formData.append("estado", estadoBD);

      const response = await fetch(
        "../../controllers/eliminar_integrar_reserva.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const respuesta = await response.json();

      if (!respuesta.success) {
        Swal.showValidationMessage(respuesta.message);
      }

      return respuesta;
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}

// Aprobar reserva
async function aprobarReserva(IDreservaBD, estadoBD, opcionBD, IDusuario) {
  Swal.fire({
    title: "Procesando...",
    text: "Por favor espera un momento.",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  const formData = new FormData();
  formData.append("IDreserva", IDreservaBD);
  formData.append("estado", estadoBD);
  formData.append("opcion", opcionBD);
  formData.append("IDusuario", IDusuario);

  const response = await fetch("../../controllers/opciones_reserva.php", {
    method: "POST",
    body: formData,
  });

  const respuesta = await response.json();

  if (respuesta.success) {
    Swal.fire({
      title: "¡Exito!",
      text: respuesta.message,
      icon: "success",
    }).then(() => {
      location.reload();
    });
  } else {
    Swal.fire({
      title: "¡Error!",
      text: respuesta.message,
      icon: "error",
    });
  }
}

// Rechazar reserva
function rechazarReserva(IDreservaBD, estadoBD, opcionBD) {
  Swal.fire({
    title: '<span class="text-danger mb-3 fw-bold"> Rechazar reserva </span>',
    html: `¿Esta seguro de rechazar esta reserva? <br>
    <strong>No. de reserva: </strong> ${IDreservaBD} <br>
    `,
    icon: "error",
    showCancelButton: true,
    confirmButtonText: "Si, rechazar reserva",
    cancelButtonText: "No, volver al listado",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    width: 600,
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDreserva", IDreservaBD);
      formData.append("estado", estadoBD);
      formData.append("opcion", opcionBD);

      const response = await fetch("../../controllers/opciones_reserva.php", {
        method: "POST",
        body: formData,
      });

      const respuesta = await response.json();

      if (!respuesta.success) {
        Swal.showValidationMessage(respuesta.message);
      }

      return respuesta;
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire("¡Exito!", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}

//  Funcion para ver el detalle de la reserva
async function verDetalle(
  IDreserva,
  nombre,
  apellido,
  tipoUsuarioBD,
  estadoBD,
  IDusuario
) {
  console.log(estadoBD);
  const formData = new FormData();
  formData.append("IDreserva", IDreserva);
  const response = await fetch("../../controllers/detalle_reserva.php", {
    method: "POST",
    body: formData,
  });

  const resultado = await response.json();

  if (resultado.success) {
    let tabla = `<h4 class="mt-2"> Propietario: <span class="fw-bold"> ${nombre} ${apellido} </span> </h4>`;
    tabla += `
                    <table class="table table-striped table-bordered" style="width:100%;text-align:left;">
                        <thead>
                            <tr>
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
      if (estadoBD == "Pendiente" || estadoBD == "Rechazada") {
        tabla += `
        <div class = "mt-3">`;

        tabla += `
                  <button class="btn btn-success fw-bold" onclick="aprobarReserva(
                     ${IDreserva}, 
                      '${estadoBD}', 
                      'Aprobar',
                      ${IDusuario} )">
                      <i class="fa-solid fa-thumbs-up"></i>
                      Aprobar reserva
                  </button>
              
          `;
      }

      if (estadoBD == "Pendiente" || estadoBD == "Aprobada") {
        tabla += `
                  <button class="btn btn-danger fw-bold" onclick="rechazarReserva(
                     ${IDreserva}, 
                      '${estadoBD}', 
                      'Rechazar')">
                      <i class="fa-solid fa-circle-xmark"></i>
                      Rechazar reserva
                  </button>
          `;
      }

      tabla += `
        <button id="btn-cancelar" class="btn btn-primary fw-bold">Cancelar</button>
      </div>
      `;
    }

    Swal.fire({
      title: `Detalle de la reserva <span class="fw-bold m-0"> #${IDreserva}</span>`,
      html: tabla,
      icon: "info",
      showConfirmButton: false,
      width: 800,
      didOpen: () => {
        const popup = Swal.getPopup();
        popup
          .querySelector("#btn-cancelar")
          .addEventListener("click", () => Swal.close("cancelar"));
      },
    });
  }
}
