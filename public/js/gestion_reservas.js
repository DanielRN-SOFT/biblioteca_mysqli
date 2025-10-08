let btnCrearReserva = document.querySelector("#BtnCrearReserva");
function crearReserva(IDcliente){
fetch("../../controllers/select_libro.php")
  .then(async (Response) => {
    if (!Response.ok) {
      throw new Error("Error en la solicitud");
    }
    return await Response.json();
  })
  .then((libros) => {
    let opciones = "";
    libros.forEach((libro) => {
      opciones += `<option class="text-center border" value= "${libro.id}"> ${libro.titulo} </option>"`;
    });
    Swal.fire({
      title: '<span class = "text-success"> Crear reserva </span>',
      html: `
        <div class="row">
    <div class="col-sm-12">
        <form action="" method="post" id="frmReservas">
            <div class="mb-3">
                <label class = "form-label" for="">Listado de libros</label>
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
      preConfirm: () => {
        const form = document.querySelector("#frmReservas");
        const formData = new FormData(form);
        return fetch("../../controllers/agregar_reserva.php", {
            method: "POST",
            body: formData
        })
        .then(async response =>{
            if(!response.ok){
                throw new Error("ERROR EN LA SOLICITUDA");
            }
            return await response.json();
        })
        .then(respuesta =>{
            if(!respuesta.success){
                Swal.showValidationMessage(respuesta.message);
            }

            return respuesta;
        })
      },
    }).then(resultado =>{
        if(resultado.value.success && resultado.isConfirmed){
            Swal.fire("Exito", resultado.value.message, "success").then(()=>{
                location.reload();
            })
        }
    });
  });
}

