function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

// Agregar categoria
let btnCrear = document.querySelector("#crearCategoria");
btnCrear.addEventListener("click", () => {
  Swal.fire({
    title: '<span class="text-success fw-bold">Agregar Categoria</span>',
    html: `<form action="" method="post" id="frmCrearCategoria">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Categoria:</label>
        <input class="form-control text-center" type="text" id="categoria" name="categoria"
        onkeyup="buscarCategoria(this.value)"/>
      </div>

      <div id="sugerenciasCategoria" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
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
      const form = document.getElementById("frmCrearCategoria");
      const formData = new FormData(form);
      return $.ajax({
        url: "../../controllers/agregarCategoria.php",
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

function buscarCategoria(texto) {
  if (texto.length < 2) {
    document.getElementById("sugerenciasCategoria").innerHTML = "";
    return;
  }

  let tablaBody = document.querySelector("#t-body");

  $.ajax({
    url: "../../controllers/buscar_categoria.php",
    type: "POST",
    data: { query: texto },
    success: function (response) {
      const categorias = JSON.parse(response);

      
      let html = `<ul class="list-group">`;

      categorias.forEach((categoria) => {
        html += `
            <li class = "list-group-item list-group-item-action mb-2 text-center">
            <i class="fa-solid fa-book"></i> Categoria existente: <strong> ${categoria.nombre_categoria} </strong> 
            </li>
        `;
      });

      html += "</ul>";
      document.getElementById("sugerenciasCategoria").innerHTML = html;
    },
  });
}

//EDITAR CATEGORIA
function editarCategoria(IDcategoria) {
  // Acceder a datos del usuario a editar con AJAX
  $.ajax({
    url: "../../controllers/datosEditarCategorias.php",
    type: "POST",
    data: { IDcategoria: IDcategoria },
    dataType: "json",
    success: function (data) {
      Swal.fire({
        title: '<span class="text-primary fw-bold"> Editar Categoria </span>',
        title: '<span class="text-primary fw-bold">Editar Categoria</span>',
        html: `
         <form action="" method="post" id="frmEditarCategoria">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control text-center" type="text" id="categoria" name="categoria" onkeyup="buscarCategoria(this.value)" value="${data.nombre_categoria}" />
      </div>

      <div id="sugerenciasCategoria" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
       <input
            class="form-control"
            type="hidden"
            id="IDcategoria"
            name="IDcategoria"
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
          const formulario = document.getElementById("frmEditarCategoria");
          const formData = new FormData(formulario);
          // Esperar un retorno de respuesta en JSON por via AJAX
          return $.ajax({
            url: "../../controllers/editarCategoria.php",
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
// ELIMINAR CATEGORIA
function eliminarCategoria(idCategoria, estado, categoria) {
  Swal.fire({
    title: '<span class = "text-danger fw-bold"> Eliminar Categoria </span>',
    html: `¿Esta seguro de realizar esta accion?
    <br> Categoria: <strong> ${categoria} </strong>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar categoria",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      cargandoAlerta("Eliminando Registro...");
      return $.ajax({
        url: "../../controllers/eliminar_reintegrar_categoria.php",
        type: "POST",
        data: {
          id: idCategoria,
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
      Swal.fire("Ocurrio un error...", resultado.value.message, "error");
    }
  });
}
// Reintegrar Categoria
function reintegrarCategoria(idCategoria, estado) {
  console.log(estado);
  Swal.fire({
    title: "<span class='text-success fw-bold'> Reintegrar Categoria </span>",
    html: "¿Esta seguro de reintegrar esta categoria?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, reintegrar categoria",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: () => {
      cargandoAlerta("Reintegrando Registro...");
      return $.ajax({
        url: "../../controllers/eliminar_reintegrar_categoria.php",
        type: "POST",
        data: {
          id: idCategoria,
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
