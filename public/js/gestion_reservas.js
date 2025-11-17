function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

async function crearReserva(IDcliente, tipoUsuarioBD) {
  const hoy = new Date().toISOString().split("T")[0];
  const unMesDespues = new Date();
  unMesDespues.setMonth(unMesDespues.getMonth() + 1);
  fechaMaxima = unMesDespues.toISOString().split("T")[0];
  Swal.fire({
    title: '<span class="text-success fw-bold fs-1">Crear reserva</span>',
    html: `
      <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar libro..." onkeyup="buscarProducto(this.value)">
        <div class= "mb-2">
 <label for="fecha" class="fw-bold form-label">
       Día programado de visita:
      </label>
      <input type="date" id="fechaAsistencia" class="swal2-input" min="${hoy}" max="${fechaMaxima}" value="${hoy}" style="width:auto;">
    </div> 
      <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-striped table-bordered" style="width:100%;text-align:left; margin-top:10px;" id="tablaProductos">
        <thead>
          <tr> 
            <th><i class="fa-solid fa-book text-primary"></i> Título</th>
            <th><i class="fa-solid fa-circle-user text-success"></i> Autor</th>
            <th><i class="fa-solid fa-book-open text-warning"></i> Categoría</th>
            <th> <i class ="fa-solid fa-square-xmark text-danger"></i> Acción</th>
          </tr>
        </thead>
        <tbody id="t-body"></tbody>
      </table>
    `,
    width: 1000,
    showCancelButton: true,
    confirmButtonText: "Guardar reserva",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },

    preConfirm: async () => {
      try {
        // Fecha de asistencia
        const fechaAsistencia =
          document.querySelector("#fechaAsistencia").value;
        console.log(fechaAsistencia);
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

        cargandoAlerta("Creando reserva...");

        // Enviar datos al servidor para guardar la reserva
        const respuesta = await fetch("../../controllers/agregar_reserva.php", {
          method: "POST",
          body: new URLSearchParams({
            libros: JSON.stringify(productos),
            IDcliente: IDcliente,
            fechaAsistencia: fechaAsistencia,
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
      Swal.fire({
        title: "¡Exito!",
        text: resultado.value,
        icon: "success",
        confirmButtonText: "Aceptar"
      }).then(() => {
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

  $.ajax({
    url: "../../controllers/buscar_libros_reserva.php",
    type: "POST",
    data: { query: texto },
    success: function (response) {
      const busqueda = JSON.parse(response);
      const sugerencias = document.querySelector("#sugerencias");
      sugerencias.innerHTML = ""; // Limpia las sugerencias anteriores

      const listGroup = document.createElement("ul");
      listGroup.classList.add("list-group");

      if (busqueda.libros.length === 0) {
        const sinResultados = document.createElement("li");
        sinResultados.classList.add(
          "list-group-item",
          "text-muted",
          "text-center"
        );
        sinResultados.id = "sinResultados";
        sinResultados.innerText = "No se encontraron resultados";
        listGroup.appendChild(sinResultados);
      } else {
        busqueda.libros.forEach((libro) => {
          const categoriasString = busqueda.categorias
            .filter((cat) => cat.libro_id == libro.id)
            .map((cat) => cat.nombre_categoria)
            .join(" - ");

          const li = document.createElement("li");
          li.classList.add("list-group-item", "list-group-item-action");
          li.innerHTML = `
            <strong><i class="fa-solid fa-book text-primary"></i> Título:</strong> ${libro.titulo}
            - <strong><i class="fa-solid fa-circle-user text-success"></i> Autor:</strong> ${libro.autor}
            - <strong><i class="fa-solid fa-book-open text-warning"></i> Categoría:</strong> ${categoriasString}
          `;

          li.addEventListener("click", () => {
            agregarLibro(libro.id, libro.titulo, libro.autor, categoriasString);
          });

          listGroup.appendChild(li);
        });
      }

      sugerencias.appendChild(listGroup);
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
async function cancelarReserva(IDreservaBD, estadoBD) {
  const formData = new FormData();
  formData.append("IDreserva", IDreservaBD);
  formData.append("estado", estadoBD);

  cargandoAlerta("Cancelando reserva...");
  const response = await fetch(
    "../../controllers/eliminar_integrar_reserva.php",
    {
      method: "POST",
      body: formData,
    }
  );

  const respuesta = await response.json();

  if (respuesta.success) {
    Swal.fire({
      title: "¡Exito!",
      text: respuesta.message,
      icon: "success",
      timer: 2000,
      showConfirmButton: false,
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

// Aprobar reserva
async function aprobarReserva(IDreservaBD, estadoBD, opcionBD, IDusuario) {
  cargandoAlerta("Procesando aprobacion de reserva...");

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
      timer: 2000,
      showConfirmButton: false,
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
async function rechazarReserva(IDreservaBD, estadoBD, opcionBD) {
  cargandoAlerta("Procesando rechazo de reserva...");

  const formData = new FormData();
  formData.append("IDreserva", IDreservaBD);
  formData.append("estado", estadoBD);
  formData.append("opcion", opcionBD);

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
      timer: 2000,
      showConfirmButton: false,
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

  cargandoAlerta("Cargando detalle...");

  const response = await fetch("../../controllers/detalle_reserva.php", {
    method: "POST",
    body: formData,
  });

  const resultado = await response.json();

  let arregloCategorias = [];

  if (resultado.success) {
    let tabla = `<h4 class="mt-1 mb-3"> Propietario: <span class="fw-bold"> ${nombre} ${apellido} </span> </h4>`;
    tabla += `
                    <table class="table table-striped table-bordered" style="width:100%;text-align:left;">
                      <thead>
                          <tr> 
                          <th><i class="fa-solid fa-book text-primary"></i> Título</th>
                          <th><i class="fa-solid fa-circle-user text-success"></i> Autor</th>
                          <th><i class="fa-solid fa-circle-info text-danger"></i> ISBN</th>
                          <th><i class="fa-solid fa-book-open text-warning"></i> Categoría</th>
                        
                        </tr>
                      </thead>

                        
                        <tbody>
                `;

    resultado.detalle.forEach((item) => {
      resultado.categorias.forEach((cat) => {
        if (item.id == cat.libro_id) {
          arregloCategorias.push(cat.nombre_categoria);
        }
      });

      let categoriasString = arregloCategorias.join("-");
      arregloCategorias = [];

      tabla += `
                        <tr class="p-5">
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
      if (estadoBD == "Pendiente") {
        tabla += `
        <div class = "mt-4">`;

        tabla += `
                  <button class="btn btn-success fw-bold mx-1" onclick="aprobarReserva(
                     ${IDreserva}, 
                      '${estadoBD}', 
                      'Aprobar',
                      ${IDusuario} )">
                      <i class="fa-solid fa-thumbs-up"></i>
                      Aprobar reserva
                  </button>
              
          `;
      }

      if (estadoBD == "Pendiente") {
        tabla += `
                  <button class="btn btn-danger fw-bold mx-1" onclick="rechazarReserva(
                     ${IDreserva}, 
                      '${estadoBD}', 
                      'Rechazar')">
                      <i class="fa-solid fa-circle-xmark"></i>
                      Rechazar reserva
                  </button>
          `;
      }
    }

    if (estadoBD == "Pendiente") {
      tabla += `
       <button class="btn btn-warning fw-bold mx-1" onclick="cancelarReserva(
                   ${IDreserva} , 
                    '${estadoBD}')">
                    <i class="fa-solid fa-trash"></i>
                    Cancelar reserva
        </button>
      `;
    }

    tabla += `
        <button id="btn-cancelar" class="btn btn-primary mx-1 mt-2 mt-sm-0 fw-bold">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Volver al listado
        </button>
      </div>
      `;

    Swal.fire({
      title: `Detalle de la reserva <span class="fw-bold m-0"> #${IDreserva}</span>`,
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
