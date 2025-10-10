let btnAgregar = document.querySelector("#crearLibro");
btnAgregar.addEventListener("click", () => {
  Swal.fire({
    title: '<h1 class="text-success fw-bolder">Añadir Libro</h1>',
    html: `
         <form action="" method="post" id="frmCrearLibro">
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
      confirmButton: "btn btn-success fs-5",
      cancelButton: "btn btn-danger fs-5",
    },
    preConfirm: () => {
      const form = document.getElementById("frmCrearUsuario");
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
    url: "../../controllers/datos_editarLibro.php",
    type: "POST",
    data: { IDlibro: IDlibro },
    dataType: "json",
    success: function (data) {
      Swal.fire({
        title: '<span class ="text-primary fw-bold"> Editar Libro </span>',
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
// ELIMINAR EMPLEADO
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
// Reintegrar empleado
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