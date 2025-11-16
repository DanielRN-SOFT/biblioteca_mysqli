function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

// Agregar Libro
let btnCrear = document.querySelector("#crearLibro");
btnCrear.addEventListener("click", async () => {
  const request = await fetch("../../controllers/datos_categorias.php", {
    method: "POST",
  });

  const response = await request.json();
  console.log(response.categorias[0]);
  let opciones = [];

  response.categorias.forEach((cat) => {
    opcion = `<option value = "${cat.id}"> ${cat.nombre_categoria} </option>`;
    opciones.push(opcion);
  });

  console.log(opciones);

  Swal.fire({
    title: '<span class="text-success fw-bold">Agregar Libro</span>',
    html: `<form action="" method="post" id="frmCrearLibro">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control text-center" type="text" id="titulo" name="titulo" 
        onkeyup="buscarLibro(this.value)"/>
      </div>

       <div id="sugerenciasLibro" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>

      <div class="mb-3">
        <label for="autor" class="form-label">Autor:</label>
        <input class="form-control text-center" type="text" id="autor" name="autor" />
      </div>

      <div class="mb-3">
        <label for="isbn" class="form-label">ISBN:</label>
        <input class="form-control text-center" type="text" id="isbn" name="isbn" />
      </div>


      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad:</label>
        <input class="form-control text-center" type="number" id="cantidad" name="cantidad" />
      </div>

      <div class="mb-3">
       <div class="mb-3">
       <label class="form-label fw-bold">Categorías</label>
            <select id="selectCategorias" name="categorias[]" class="form-control text-center" multiple>
               ${opciones}
            </select>
      </div>
  
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
    didOpen: () => {
      // INTEGRACIÓN SELECT2
      $("#selectCategorias").select2({
        language: "es",
        width: "100%",
        placeholder: "Seleccione las categorías del libro",
        dropdownParent: $(".swal2-popup"),
      });
    },
    preConfirm: () => {
      const titulo = document.querySelector("#titulo").value;
      const autor = document.querySelector("#autor").value;
      const isbn = document.querySelector("#isbn").value;
      const cantidad = document.querySelector("#cantidad").value;


      if (titulo.length === 0 || autor.length === 0 || isbn.length === 0) {
        Swal.showValidationMessage("Todos los campos son obligatorios");
        return false;
      }

      if (cantidad.length === 0 || isNaN(cantidad)) {
        Swal.showValidationMessage("Ingrese un valor valido en la cantidad");
        return false;
      }

      const form = document.getElementById("frmCrearLibro");
      const formData = new FormData(form);
      return $.ajax({
        url: "../../controllers/agregar_libro.php",
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

function buscarLibro(texto) {
  if (texto.length < 3) {
    document.getElementById("sugerenciasLibro").innerHTML = "";
    return;
  }

  let tablaBody = document.querySelector("#t-body");

  $.ajax({
    url: "../../controllers/buscar_libros_reserva.php",
    type: "POST",
    data: { query: texto },
    success: function (response) {
      const busqueda = JSON.parse(response);

      let html = `<ul class="list-group">`;

      busqueda.libros.forEach((libro) => {
        html += `
            <li class = "list-group-item list-group-item-action mb-2 text-center">
            <i class="fa-solid fa-book"></i> Libro existente: <strong> ${libro.titulo} </strong> 
            </li>
        `;
      });

      html += "</ul>";
      document.getElementById("sugerenciasLibro").innerHTML = html;
    },
  });
}



//EDITAR LIBRO
function editarLibro(IDlibro) {
  // Acceder a datos del usuario a editar con AJAX
  $.ajax({
    url: "../../controllers/datos_editar.php",
    type: "POST",
    data: { IDlibro: IDlibro },
    dataType: "json",
    success: function (data) {
      // Todas las categorías (objeto completo)
      const todasCategorias = data.categorias;

      // Solo IDs de categorías seleccionadas
      const seleccionadas = data.categoriasSeleccionadas.map((c) => c.id);

      // Crear las opciones
      const opcionesSelect = todasCategorias
        .map(
          (cat) => `
          <option value="${cat.id}" ${
            seleccionadas.includes(cat.id) ? "selected" : ""
          }>
            ${cat.nombre_categoria}
          </option>
        `
        )
        .join("");

      Swal.fire({
        title: '<span class="text-primary fw-bold"> Editar Libro </span>',
        title: '<span class="text-primary fw-bold">Editar Libro</span>',
        html: `
         <form action="" method="post" id="frmEditarLibro">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control text-center" type="text" id="titulo" name="titulo" value="${data.datosLibro.titulo}" />
      </div>

      <div class="mb-3">
        <label for="autor" class="form-label">Autor:</label>
        <input class="form-control text-center" type="text" id="autor" name="autor" value="${data.datosLibro.autor}"/>
      </div>

      <div class="mb-3">
        <label for="isbn" class="form-label">ISBN:</label>
        <input class="form-control text-center" type="text" id="isbn" name="isbn" disabled value="${data.datosLibro.ISBN}"/>
      </div>

      
      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad:</label>
        <input class="form-control text-center" type="number" id="cantidad" name="cantidad" value="${data.datosLibro.cantidad}"/>
      </div>
      <div class="mb-3">
       <label class="form-label fw-bold">Categorías</label>
            <select id="selectCategorias" name="categorias[]" class="form-control" multiple>
               ${opcionesSelect}
            </select>
      </div>

      <div id="contenedor-tabla"> </div>

      <input type="hidden" value="${IDlibro}" name="IDlibro" id="IDlibro">

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
        didOpen: () => {
          // INTEGRACIÓN SELECT2
          $("#selectCategorias").select2({
            language: "es",
            width: "100%",
            placeholder: "Seleccione las categorías del libro",
            dropdownParent: $(".swal2-popup"),
          });
        },
        // Antes de finalizar la accion, realize esta cuestion
        preConfirm: () => {
          // Recolectar las categorias seleccionadas
          const titulo = document.querySelector("#titulo").value;
          const autor = document.querySelector("#autor").value;

          if (titulo.length === 0 || autor.length === 0) {
            Swal.showValidationMessage("Todos los campos son obligatorios");
            return false;
          }

          // Acceder a los datos ingresados en el formulario
          const formulario = document.getElementById("frmEditarLibro");
          const formData = new FormData(formulario);
          // formData.append("categorias", JSON.stringify(categorias));
          // Esperar un retorno de respuesta en JSON por via AJAX
          return $.ajax({
            url: "../../controllers/editar_libro.php",
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
// ELIMINAR LIBRO
function eliminarLibro(idLibro, estado, libro) {
  Swal.fire({
    title: '<span class = "text-danger fw-bold"> Eliminar Libro </span>',
    html: `¿Esta seguro de realizar esta accion?
    <br> Titulo: <strong> ${libro} </strong>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar libro",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      cargandoAlerta("Eliminando Registro...");
      return $.ajax({
        url: "../../controllers/eliminar_reintegrar_libro.php",
        type: "POST",
        data: {
          id: idLibro,
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
      cargandoAlerta("Reintegrando Registro...");
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
