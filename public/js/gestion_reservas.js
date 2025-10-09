// Funcion para traer todos los libros en un option
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

// Crear reserva
async function crearReserva(IDcliente) {
  let opciones = await selectLibros();
  Swal.fire({
    title: '<span class = "text-success"> Crear reserva </span>',
    html: `
        <div class="row">
    <div class="col-sm-12">
        <form action="" method="post" id="frmReservas">
            <div class="mb-3">
                <label class = "form-label" for="">Seleccione los libros a reservar</label>
                <select class="form-select" name="libros[]" id="libros" multiple aria-label="multiple select example">
                 ${opciones}
                </select>
               
            </div>
            <input type = "hidden" value="${IDcliente}" name="IDcliente"> 
        </form>
    </div>
</div>
        `,
    showCancelButton: true,
    confirmButtonText: "Agregar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    preConfirm: async () => {
      const form = document.querySelector("#frmReservas");
      const formData = new FormData(form);

      // Fetch api asincronico
      const response = await fetch("../../controllers/agregar_reserva.php", {
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

// Editar reserva
async function editarReserva(IDreservaBD, IDlibroBD) {
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
    title: '<span class="text-primary"> Editar reserva </span>',
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
    preConfirm: async () => {
      try {
        // Enviar los datos del formulario y los IDs para la peticion FETCH
        const form = document.querySelector("#frmEditarReservas");
        const formData = new FormData(form);
        formData.append("reserva_id", IDreservaBD);
        formData.append("libro_id", IDlibroBD);

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
