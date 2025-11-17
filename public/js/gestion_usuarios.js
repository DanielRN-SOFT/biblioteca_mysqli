function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

// CREAR usuario
let btnCrear = document.querySelector("#BtnCrearUsuario");
btnCrear.addEventListener("click", () => {
  Swal.fire({
    title: '<span class="text-success fw-bold">Crear usuario</span>',
    html: `
              <form action="" method="post" id="frmCrearUsuario">
      <div class="row">
        <div class="col-sm-12">   
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input
                  class="form-control text-center"
                  type="text"
                  id="nombre"
                  name="nombre"
                />
              </div>
              <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input
                  class="form-control text-center"
                  type="text"
                  id="apellido"
                  name="apellido"
                />
              </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input class="form-control text-center" type="email" id="email" name="email" />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input
              class="form-control text-center"
              type="password"
              id="password"
              name="password"
            />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Tipo:</label>
            <select class="form-control text-center" name="tipo" id="tipo">
              <option value="Administrador">Administrador</option>
              <option value="Cliente">Cliente</option>
            </select>
          </div>
        </div>
      </div>
    </form>

        `,
    showCancelButton: true,
    confirmButtonText: "Agregar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      const form = document.getElementById("frmCrearUsuario");
      const formData = new FormData(form);

      return $.ajax({
        url: "../../controllers/agregar_usuario.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        beforeSend: () => {
          Swal.showLoading(); // loading dentro del MISMO Swal
        },
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

// EDITAR USUARIO
function editarUsuario(IDuser) {
  // Acceder a datos del usuario a editar con AJAX
  $.ajax({
    url: "../../controllers/datos_editar.php",
    type: "POST",
    data: { IDusuario: IDuser },
    dataType: "json",
    success: function (data) {
      // En caso de que finalize con exito, disparar la alerta
      // El parametro de la funcion permite acceder a cada valor
      // del JSON por medio del operador de objetos '.'
      Swal.fire({
        title: '<span class ="text-primary fw-bold"> Editar usuario </span>',
        html: `
           <form action="" method="post" id="frmEditarUsuario">
      <div class="row">
        <div class="col-sm-12">
         
            
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input
                  class="form-control text-center"
                  type="text"
                  id="nombre"
                  name="nombre"
                  value="${data.nombre}"
                />
              </div>
              <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input
                  class="form-control text-center"
                  type="text"
                  id="apellido"
                  name="apellido"
                  value="${data.apellido}"
                />
              </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input
              class="form-control text-center"
              type="email"
              id="email"
              name="email"
              value="${data.email}"
            />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Tipo:</label>
            <select class="form-control text-center" name="tipo" id="tipo">
              <option value="Administrador" ${
                data.tipo == "Administrador" ? "selected" : ""
              }>Administrador</option>
              <option value="Cliente" ${
                data.tipo == "Cliente" ? "selected" : ""
              }>Cliente</option>
            </select>
          </div>

          <input
            class="form-control"
            type="hidden"
            id="IDusuario"
            name="IDusuario"
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
          confirmButton: "btn btn-success fw-bold",
          cancelButton: "btn btn-danger fw-bold",
        },
        // Antes de finalizar la accion, realize esta cuestion
        preConfirm: () => {
          // Acceder a los datos ingresados en el formulario
          const formulario = document.getElementById("frmEditarUsuario");
          const formData = new FormData(formulario);

          // Esperar un retorno de respuesta en JSON por via AJAX
          return $.ajax({
            url: "../../controllers/editar_usuario.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: () => {
              Swal.showLoading(); // loading dentro del MISMO Swal
            },
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

// ELIMINAR USUARIO
function eliminarUsuario(IDusuario, estado, nombre, apellido) {
  Swal.fire({
    title: '<span class = "text-danger fw-bold"> Eliminar usuario </span>',
    html: `¿Esta seguro de realizar esta accion? 
    <br> Nombre: <strong> ${nombre} ${apellido}  </strong>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar usuario",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      return $.ajax({
        url: "../../controllers/eliminar_integrar_usuario.php",
        type: "POST",
        data: {
          id: IDusuario,
          estado: estado,
        },
        dataType: "json",
        beforeSend: () => {
          Swal.showLoading(); // loading dentro del MISMO Swal
        },
      }).then((respuesta) => {
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
    } else {
      Swal.fire({
        title: "Ocurrio un error...",
        text: resultado.value.message,
        icon: "error",
      });
    }
  });
}

// Reintegrar usuario
function reintegrarUsuario(IDusuario, estado, nombre, apellido) {
  console.log(estado);
  Swal.fire({
    title: "<span class='text-success fw-bold'> Reintegrar usuario </span>",
    html: `¿Esta seguro de reintegrar este usuario? 
    <br> Nombre: <strong> ${nombre} ${apellido} </strong>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, reintegrar usuario",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: () => {
      cargandoAlerta("Reintegrando Usuario...");
      return $.ajax({
        url: "../../controllers/eliminar_integrar_usuario.php",
        type: "POST",
        data: {
          id: IDusuario,
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
