// // Crear reserva
// function crearReserva(IDcliente) {
//   Swal.fire({
//     title: '<span class = "text-success fw-bold"> Crear reserva </span>',
//     html: `
//         <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar libro..." onkeyup="buscarProducto(this.value)">
//           <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
//           <table class = "table table-bordered" id="tablaProductos" style="margin-top:10px; font-size:14px;">
//             <thead>
//               <tr>
//                 <th>Titulo</th>
//                 <th>Autor</th>
//                 <th>Categoria</th>
//                 <th>Accion</th>
//               </tr>
//             <tbody></tbody>
//             </table>
//         `,
//         width:800,
//     showCancelButton: true,
//     confirmButtonText: "Guardar reserva",
//     cancelButtonText: "Cancelar",
//     customClass: {
//       confirmButton: "btn btn-success",
//       cancelButton: "btn btn-danger",
//     },
//     preConfirm: () => {
//       return new Promise((resolve, reject) => {

//         $.ajax({
//           url: "../../controllers/contar_reservas.php",
//           type: "POST",
//           data: {"IDcliente": IDcliente},
//           dataType: "json",
//           success: (resultado) =>{
//             if(!resultado.success){
//               reject(resultado.message)
//               return
//             }
//           }
//         })

//         const productos = [];

//         document.querySelectorAll('#tablaProductos tbody tr').forEach(row => {
//           const IDlibro = parseInt(row.getAttribute("data-id"));

//           if (IDlibro > 0) {
//             productos.push(IDlibro);
//           }
//         })

//         if(productos.length === 0){
//           reject('Agregue al menos un libro para completar la reserva');
//           return;
//         }

//         $.ajax({
//           url: '../../controllers/agregar_reserva.php',
//           type: "POST",
//           data:{
//             libros: JSON.stringify(productos),
//             IDcliente: IDcliente
//           },
//           success: function(response){
//             const res = JSON.parse(response);
//             console.log(res);
//             if(res.success){
//               resolve(res.message);
//             }else{
//               reject(res.message);
//             }
//           },
//           error: function(){
//             reject('No se pudo guardar a venta.');
//           }
//         })
//       }).catch(error =>{
//         Swal.showValidationMessage(error);
//       })
//     },
//   }).then((resultado) => {
//     if (resultado.isConfirmed && resultado.value) {
//       Swal.fire("¡Exito!", resultado.value, "success").then(() => {
//         location.reload();
//       });
//     }
//   });
// }

async function crearReserva(IDcliente) {
  Swal.fire({
    title: '<span class="text-success fw-bold">Crear reserva</span>',
    html: `
      <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar libro..." onkeyup="buscarProducto(this.value)">
      <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-bordered" id="tablaProductos" style="margin-top:10px; font-size:14px;">
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
                <strong> Titulo:  </strong>${libro.titulo} 
              - <strong> Autor:  </strong> ${libro.autor} 
              - <strong> Categoria:  </strong> ${libro.categoria}
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

// Funcion para traer todos los libros en un option en el EDITAR
async function selectLibros(IDlibro) {
  let opciones = "";
  const response = await fetch("../../controllers/seleccionar_libros.php");
  const libros = await response.json();

  // Ciclo para recorrer el arreglo JSON
  libros.forEach((libro) => {
    const selected = IDlibro == libro.id ? "selected" : "";
    opciones += `<option ${selected} class="text-center border" value="${libro.id}"> ${libro.titulo} </option>`;
  });

  return opciones;
}

// Editar reserva
async function editarReserva(IDreservaBD, IDlibroBD, estadoBD, tipoUsuarioBD) {
  console.log(estadoBD);
  // Mandar los IDs a la peticion Fetch
  const datosEditar = new FormData();
  datosEditar.append("IDreserva", IDreservaBD);
  datosEditar.append("IDlibro", IDlibroBD);

  // Fetch ASINCRONICO
  const response = await fetch("../../controllers/datos_editar_reserva.php", {
    method: "POST",
    body: datosEditar,
  });

  // Respuesta JSON parseada
  const datos = await response.json();

  // Opciones de libros
  const opciones = await selectLibros(datos.id);

  Swal.fire({
    title: '<span class="text-primary fw-bold"> Editar reserva </span>',
    html: `
      <div class="row">
        <div class="col-sm-12">
          <form action="" method="post" id="frmEditarReservas">
            <div class="mb-3">
            <p> <span class="fw-bold"> Reserva: </span> ${IDreservaBD} </p>
              <label class="form-label" for="libro">Seleccione un nuevo libro</label>
              <select class="form-select text-center mt-2" name="libro" id="libro" aria-label="select example">
                ${opciones}
              </select>
            </div>
          </form>
        </div>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: "Guardar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    width: 600,
    preConfirm: async () => {
      try {
        // Enviar los datos del formulario y los IDs para la peticion FETCH
        const form = document.querySelector("#frmEditarReservas");
        const formData = new FormData(form);
        formData.append("reserva_id", IDreservaBD);
        formData.append("libro_id", IDlibroBD);
        formData.append("estado", estadoBD);
        formData.append("tipoUsuario", tipoUsuarioBD);

        // Fetch API
        const response = await fetch("../../controllers/editar_reserva.php", {
          method: "POST",
          body: formData,
        });

        // Respuesta parseada en JSON
        const respuesta = await response.json();

        // Mostrar un mensaje de error en caso de FALSE
        if (!respuesta.success) {
          Swal.showValidationMessage(respuesta.message);
        }

        // Retornar la respuesta en caso de TRUE
        return respuesta;
      } catch (error) {
        Swal.showValidationMessage(`Error: ${error.message}`);
      }
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}

// Cancelar reserva
function cancelarReserva(IDreservaBD, IDlibroBD, estadoBD) {
  Swal.fire({
    title: '<span class="text-danger mb-3 fw-bold"> Cancelar reserva </span>',
    html: `¿Esta seguro de cancelar esta reserva?: <br>
    <strong>No. de reserva: </strong> ${IDreservaBD} <br>
    (Se cancelaran todas las reservas con dicho numero) <br> 
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
      formData.append("IDlibro", IDlibroBD);
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
function reintegrarReserva(IDreservaBD, IDlibroBD, estadoBD) {
  Swal.fire({
    title: '<span class="text-success mb-3 fw-bold"> Reactivar reserva </span>',
    html: `¿Esta seguro de volver a activar esta reserva?: <br>
    <strong>No. de reserva: </strong> ${IDreservaBD} <br>
    (Se reactivaran todas las reservas con dicho numero) <br>
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
      formData.append("IDlibro", IDlibroBD);
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
function aprobarReserva(IDreservaBD, IDlibroBD, estadoBD, opcionBD) {
  Swal.fire({
    title: '<span class="text-success mb-3 fw-bold"> Aprobar reserva </span>',
    html: `¿Esta seguro de aprobar esta reserva?: <br>
    <strong>No. de reserva: </strong> ${IDreservaBD} <br>
    `,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, aprobar reserva",
    cancelButtonText: "No, volver al listado",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDlibro", IDlibroBD);
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
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}

// Rechazar reserva
function rechazarReserva(IDreservaBD, IDlibroBD, estadoBD, opcionBD) {
  Swal.fire({
    title: '<span class="text-danger mb-3 fw-bold"> Rechazar reserva </span>',
    html: `¿Esta seguro de aprobar esta reserva?: <br>
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
    preConfirm: async () => {
      const formData = new FormData();
      formData.append("IDlibro", IDlibroBD);
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
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
}
//buscar reservas
let btnBuscar = document.querySelector("#crearBusqueda");
btnBuscar.addEventListener("click", () => {
  Swal.fire({
    title: '<h2 class="text-primary fw-bolder">Buscar Reserva</h2>',
    html: `
      <input type="text" id="busquedaReserva" class="swal2-input" placeholder="Buscar Reserva...">
      <div id="sugerencias" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-bordered" id="tablaReserva" style="margin-top:10px; font-size:14px;">
        <thead>
          <tr>
            <th>Reserva</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha Reserva</th>
            <th>Libro</th>
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
      const inputBusqueda = document.getElementById("busquedaReserva");
      const tablaBody = document.querySelector("#tablaReserva tbody");

      inputBusqueda.addEventListener("keyup", function () {
        const texto = this.value.trim();

        // Si hay menos de 2 caracteres, limpia la tabla
        if (texto.length < 2) {
          tablaBody.innerHTML = "";
          return;
        }

        buscarReservas(texto, tablaBody);
      });
    },
  });
});
 // función para buscar reservas
function buscarReservas(texto, tablaBody) {
  texto = texto.trim(); // se debe reasignar

  $.ajax({
      url: "../../controllers/buscar_reservas.php",
      type: "POST",
      data: { query: texto },
      success: function (reservas) {
        console.log("Datos recibidos:", reservas);
        tablaBody.innerHTML = "";

        if (reservas.length === 0) {
          tablaBody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center text-muted">No se encontraron resultados</td>
            </tr>`;
          return;
        }

        reservas.forEach((res) => {
          tablaBody.innerHTML += `
            <tr>
              <td>${res.id}</td>
              <td>${res.nombre}</td>
              <td>${res.apellido}</td>
              <td>${res.fecha_reserva}</td>
              <td>${res.titulo}</td>
              <td>${res.estado}</td>
            </tr>
          `;
        });
      },
    });
}