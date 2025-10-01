// CREAR empleado
let btnCrear = document.querySelector("#BtnCrearUsuario");
btnCrear.addEventListener("click", () => {
  Swal.fire({
    title: '<h1 class="text-success fw-bolder">Crear usuario<h1>',
    html: `
          <form action="" method="post" id="frmCrearUsuario">
      <div class="row">
        <div class="col-sm-12">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input class="form-control" 
            type="text"
            id="nombre" 
            name="nombre" />
          </div>

          <div class="mb-3">
            <label for="apellido" class="form-label">Apellido:</label>
            <input
              class="form-control"
              type="text"
              id="apellido"
              name="apellido"
            />
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input
                  class="form-control"
                  type="email"
                  id="email"
                  name="email"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input
                  class="form-control"
                  type="password"
                  id="password"
                  name="password"
                />
              </div>
            </div>
            <div class="col-sm-6">
               <div class="mb-3">
                <label for="password" class="form-label">Tipo:</label>
                <select class="form-control" name="tipo" id="tipo">
                  <option value="Administrador">Administrador</option>
                  <option value="Cliente">Cliente</option>
                </select>
              </div>
         
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
      confirmButton: "btn btn-success fs-5",
      cancelButton: "btn btn-danger fs-5",
    },
    preConfirm: () => {
      const form = document.getElementById("frmCrearUsuario");
      const formData = new FormData(form);
      return $.ajax({
        url: "../../controllers/crear_usuario.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json"
      }).then(respuesta =>{
        if(!respuesta.success){
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
