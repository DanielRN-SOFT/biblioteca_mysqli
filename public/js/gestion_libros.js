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
btnCrear.addEventListener("click", () => {
  Swal.fire({
    title: '<span class="text-success fw-bold">Agregar Libro</span>',
    html: `<form action="" method="post" id="frmCrearLibro">
  <div class="row">
    <div class="col-sm-12">
      <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input class="form-control text-center" type="text" id="titulo" name="titulo" />
      </div>

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
       <label for="cantidad" class="form-label">Categoria:</label>
         <input type="text" id="busquedaCategoria" class="form-control" placeholder="Buscar categoria..." onkeyup="buscarCategoria(this.value)">
        
      <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-striped table-bordered" style="width:100%;text-align:left; margin-top:10px;" id="tablaCategoria">
        <thead>
          <tr> 
            <th><i class="fa-solid fa-book-open-reader text-primary"></i> Categoría</th>
            <th><i class="fa-solid fa-square-xmark text-danger"></i> Acción</th>
          </tr>
        </thead>
        <tbody id="t-body"></tbody>
      </table>
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
      // Recolectar las categorias seleccionadas
      const categorias = [];
      const titulo = document.querySelector("#titulo").value;
      const autor = document.querySelector("#autor").value;
      const isbn = document.querySelector("#isbn").value;
      const cantidad = document.querySelector("#cantidad").value;

      document.querySelectorAll("#tablaCategoria tbody tr").forEach((row) => {
        const IDCategoria = parseInt(row.dataset.id);
        if (IDCategoria > 0) {
          categorias.push(IDCategoria);
        }
      });

      if (
        titulo.length === 0 ||
        autor.length === 0 ||
        isbn.length === 0
      ) {
       
        Swal.showValidationMessage("Todos los campos son obligatorios");
        return false;
      }


      if (cantidad.length === 0 || isNaN(cantidad)) {
        Swal.showValidationMessage("Ingrese un valor valido en la cantidad");
        return false;
      }


      if (categorias.length === 0) {
        Swal.showValidationMessage("Agregue al menos una categoria");
        return false;
      }

      const form = document.getElementById("frmCrearLibro");
      const formData = new FormData(form);
      formData.append("categorias", JSON.stringify(categorias));
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

function buscarCategoria(texto) {
  if (texto.length < 2) {
    document.getElementById("sugerencias").innerHTML = "";
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

      if (categorias.length === 0) {
        html += `
            <li class = "list-group-item text-muted text-center">
               No se encontraron resultados
            </li>
        `;
      }

      categorias.forEach((categoria) => {
        html += `
            <li class = "list-group-item list-group-item-action text-center"
              onclick = "agregarCategoria('${categoria.id}','${categoria.nombre_categoria}')">
                <strong> <i class="fa-solid fa-book-open-reader"></i> Nombre:  </strong>${categoria.nombre_categoria} 
            </li>
        `;
      });

      html += "</ul>";
      document.getElementById("sugerencias").innerHTML = html;
    },
  });
}

// Agregar producto a la tabla
function agregarCategoria(id, nombreCategoria) {
  const tabla = document.querySelector("#tablaCategoria tbody");

  if ([...tabla.querySelectorAll("tr")].some((row) => row.dataset.id === id))
    return;

  const fila = document.createElement("tr");
  fila.dataset.id = id;

  fila.innerHTML = `
    <td> ${nombreCategoria} </td>
    <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();">Quitar</button></td>
  `;

  tabla.appendChild(fila);
  document.getElementById("sugerencias").innerHTML = "";
  document.getElementById("busquedaCategoria").value = "";
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
      // Crear la tabla
      const tabla = document.createElement("table");
      tabla.className = "table table-striped table-bordered mt-3";
      tabla.style.width = "100%";
      tabla.id = "tablaCategoria";
      tabla.innerHTML = `
        <thead>
          <tr> 
            <th><i class="fa-solid fa-book-open-reader text-primary"></i> Categoría</th>
            <th><i class="fa-solid fa-square-xmark text-danger"></i> Acción</th>
          </tr>
        </thead>
        <tbody id="t-body"></tbody>
      `;

      // Agregar filas
      const tbody = tabla.querySelector("#t-body");
      data.categoriasSeleccionadas.forEach((cat) => {
        const fila = document.createElement("tr");
        fila.dataset.id = cat.id;

        fila.innerHTML = `
    <td> ${cat.nombre_categoria} </td>
    <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();">Quitar</button></td>
  `;

        tbody.appendChild(fila);
      });

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
       <label for="cantidad" class="form-label">Categoria:</label>
         <input type="text" id="busquedaCategoria" class="form-control" placeholder="Buscar categoria..." onkeyup="buscarCategoria(this.value)">
        
      <div id="sugerencias" class="mt-3" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
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
          document.getElementById("contenedor-tabla").appendChild(tabla);
        },
        // Antes de finalizar la accion, realize esta cuestion
        preConfirm: () => {
          // Recolectar las categorias seleccionadas
          const categorias = [];
          const titulo = document.querySelector("#titulo").value;
          const autor = document.querySelector("#autor").value;
          const isbn = document.querySelector("#isbn").value;

          document
            .querySelectorAll("#tablaCategoria tbody tr")
            .forEach((row) => {
              const IDCategoria = parseInt(row.dataset.id);
              if (IDCategoria > 0) {
                categorias.push(IDCategoria);
              }
            });

          if (titulo.length === 0 || autor.length === 0 || isbn.length === 0) {
            Swal.showValidationMessage("Todos los campos son obligatorios");
            return false;
          }

          if (categorias.length === 0) {
            Swal.showValidationMessage("Agregue al menos una categoria");
            return false;
          }
          // Acceder a los datos ingresados en el formulario
          const formulario = document.getElementById("frmEditarLibro");
          const formData = new FormData(formulario);
          formData.append("categorias", JSON.stringify(categorias));
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
