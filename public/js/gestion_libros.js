// Agregar Libro
let btnCrear = document.querySelector("#crearLibro");
btnCrear.addEventListener("click", () => {
  Swal.fire({
    title: '<span class="text-success fw-bold">Agregar Libro</span>',
    html: `<form action="" method="post" id="frmCrearLibro">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control" type="text" id="titulo" name="titulo" />
      </div>

      <div class="mb-3">
        <label for="autor" class="form-label">Autor:</label>
        <input class="form-control" type="text" id="autor" name="autor" />
      </div>

      <div class="mb-3">
        <label for="isbn" class="form-label">ISBN:</label>
        <input class="form-control" type="text" id="isbn" name="isbn" />
      </div>

      <div class="mb-3">
        <label for="categoria" class="form-label">Categoria:</label>
        <input class="form-control" type="text" id="categoria" name="categoria" />
      </div>

      
          <div class="mb-3">
            <label for="disponibilidad" class="form-label fw-bold">Disponibilidad:</label>
            <select class="form-control text-center" name="disponibilidad" id="disponibilidad">
              <option value="Disponible">Disponible</option>
              <option value="No Disponible">No Disponible</option>
              <option value="Reservado">Reservado</option>
            </select>
          </div>

      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad:</label>
        <input class="form-control" type="number" id="cantidad" name="cantidad" />
      </div>
    </div>
  </div>
</form>

        `,
    showCancelButton: true,
    confirmButtonText: "Agregar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: () => {
      const form = document.getElementById("frmCrearLibro");
      const formData = new FormData(form);
      return $.ajax({
        url: "../../controllers/agregar_libro.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
      }).then((respuesta) => {
        if (!respuesta.success) {
          Swal.showValidationMessage(respuesta.message);
        }
        return respuesta;
      });
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire("Exito", resultado.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
});
//EDITAR LIBRO
function editarLibro(IDlibro) {
  // Acceder a datos del usuario a editar con AJAX
  $.ajax({
    url: "../../controllers/datos_editar.php",
    type: "POST",
    data: { IDlibro: IDlibro },
    dataType: "json",
    success: function (data) {
      Swal.fire({
        title: '<span class="text-primary fw-bold"> Editar Libro </span>',
        title: '<span class="text-primary fw-bold">Editar Libro</span>',
        html: `
         <form action="" method="post" id="frmEditarLibro">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control" type="text" id="titulo" name="titulo" value="${data.titulo}" />
      </div>

      <div class="mb-3">
        <label for="autor" class="form-label">Autor:</label>
        <input class="form-control" type="text" id="autor" name="autor" value="${data.autor}"/>
      </div>

      <div class="mb-3">
        <label for="isbn" class="form-label">ISBN:</label>
        <input class="form-control" type="text" id="isbn" name="isbn" value="${data.ISBN}"/>
      </div>

      <div class="mb-3">
        <label for="categoria" class="form-label">Categoria:</label>
        <input class="form-control" type="text" id="categoria" name="categoria" value="${data.categoria}"/>
      </div>

      <div class="mb-3">
            <label for="password" class="form-label fw-bold">Tipo:</label>
            <select class="form-control text-center" name="disponibilidad" id="disponibilidad">
              <option value="Disponible">Disponible</option>
              <option value="No Disponible">No Disponible</option>
              <option value="Reservado">Reservado</option>
            </select>
          </div>

      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad:</label>
        <input class="form-control" type="number" id="cantidad" name="cantidad" value="${data.cantidad}"/>
      </div>
       <input
            class="form-control"
            type="hidden"
            id="IDlibro"
            name="IDlibro"
            value="${data.id}"
          />
    </div>
  </div>
</form>
        `,
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        // Antes de finalizar la accion, realize esta cuestion
        preConfirm: () => {
          // Acceder a los datos ingresados en el formulario
          const formulario = document.getElementById("frmEditarLibro");
          const formData = new FormData(formulario);
          // Esperar un retorno de respuesta en JSON por via AJAX
          return $.ajax({
            url: "../../controllers/editar_libro.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
          }).then((respuesta) => {
            // En caso de que la respuesta retorne false mostrar un mensaje de validacion
            if (!respuesta.success) {
              Swal.showValidationMessage(respuesta.message);
            }
            // Si no retornar la validacion
            return respuesta;
          });
        },
      }).then((resultado) => {
        // Si el resultado es exitoso y confirmado, dispare una alerta de confirmacion
        if (resultado.isConfirmed && resultado.value.success) {
          Swal.fire(
            "Actualizacion completada",
            resultado.value.message,
            "success"
          ).then(() => {
            location.reload();
          });
        }
      });
    },
  });
}
// ELIMINAR LIBRO
function eliminarLibro(idLibro, estado) {
  Swal.fire({
    title: '<span class = "text-danger fw-bold"> Eliminar Libro </span>',
    html: "¿Esta seguro de realizar esta accion?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar libro",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: () => {
      return $.ajax({
        url: "../../controllers/eliminar_reintegrar_libro.php",
        type: "POST",
        data: {
          id: idLibro,
          estado: estado,
        },
        dataType: "json",
      }).then((respuesta) => {
        if (!respuesta.success) {
          Swal.showValidationMessage(respuesta.message);
        }
        return respuesta;
      });
    },
  }).then((resultado) => {
    if (resultado.isConfirmed && resultado.value.success) {
      Swal.fire(
        "Eliminacion completada",
        resultado.value.message,
        "success"
      ).then(() => {
        location.reload();
      });
    }
  });
}
// Reintegrar LIBRO
function reintegrarLibro(idLibro, estado) {
  console.log(estado);
  Swal.fire({
    title: "<span class='text-success fw-bold'> Reintegrar Libro </span>",
    html: "¿Esta seguro de reintegrar este Libro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, reintegrar libro",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: () => {
      return $.ajax({
        url: "../../controllers/eliminar_reintegrar_libro.php",
        type: "POST",
        data: {
          id: idLibro,
          estado: estado,
        },
        dataType: "json",
      }).then((respuesta) => {
        if (!respuesta.success) {
          Swal.showValidationMessage(respuesta.message);
        }
        return respuesta;
      });
    },
  }).then((result) => {
    if (result.isConfirmed && result.value.success) {
      Swal.fire(
        "Reintegracion completada",
        result.value.message,
        "success"
      ).then(() => {
        location.reload();
      });
    }
  });
}
// BUSQUEDA Y FILTROS
let btnBuscar = document.querySelector("#crearBusqueda");

btnBuscar.addEventListener("click", () => {
  Swal.fire({
    title: '<h2 class="text-primary fw-bolder">Buscar Libro</h2>',
    html: `
      <input type="text" id="busquedaLibro" class="swal2-input" placeholder="Buscar Libro...">
      <div id="sugerencias" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-bordered" id="tablaLibro" style="margin-top:10px; font-size:14px;">
        <thead>
          <tr>
            <th>Titulo</th>
            <th>Autor</th>
            <th>ISBN</th>
            <th>Categoria</th>
            <th>Disponibilidad</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `,
    width: 800,
    showConfirmButton: false,

    // 🔹 Se ejecuta cuando el modal está completamente renderizado
    didOpen: () => {
      const inputBusqueda = document.getElementById("busquedaLibro");
      const tablaBody = document.querySelector("#tablaLibro tbody");

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


  // 🔹 función para buscar libros
  function buscarPrestamos(texto, tablaBody) {
    if (texto.length < 2) {
      tablaBody.innerHTML = "";
      return;
    }

    $.ajax({
      url: "../../controllers/buscar_libros.php",
      type: "POST",
      data: { query: texto },
      success: function (response) {
        const libros = JSON.parse(response);
        tablaBody.innerHTML = "";

        if (libros.length === 0) {
          tablaBody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center text-muted">No se encontraron resultados</td>
            </tr>`;
          return;
        }

        libros.forEach((libro) => {
          tablaBody.innerHTML += `
            <tr>
              <td>${libro.titulo}</td>
              <td>${libro.autor}</td>
              <td>${libro.ISBN}</td>
              <td>${libro.categoria}</td>
              <td>${libro.disponibilidad}</td>
              <td>${libro.cantidad}</td>
            </tr>
          `;
        });
      },
    });
  }
 

