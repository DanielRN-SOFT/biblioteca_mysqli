let btnAgregar = document.querySelector("#crearLibro");
btnAgregar.addEventListener("click", () => {
  Swal.fire({
    title: '<h1 class="text-success fw-bolder">Añadir Libro<h1>',
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
        <label for="disponibilidad" class="form-label">Disponibilidad:</label>
        <input class="form-control" type="text" id="disponibilidad" name="disponibilidad" />
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
  }).then((result) => {
    if (result.isConfirmed && result.value.success) {
      Swal.fire("Exito", result.value.message, "success").then(() => {
        location.reload();
      });
    }
  });
});
