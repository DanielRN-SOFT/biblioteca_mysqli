function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

let btnAcceder = document.querySelector("#btn-acceder");
btnAcceder.addEventListener("click", (e) => {
  e.preventDefault();

  let formulario = document.querySelector("#frmLogin");
  let formData = new FormData(formulario);
cargandoAlerta("Intentando iniciar sesion...")
  $.ajax({
    url: "../../controllers/login.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (respuesta) {
      if (!respuesta.success) {
        Swal.fire({
          title: '<span class="fs-2 fw-bold"> Error </span>',
          text: respuesta.message,
          icon: "error",
        });
      } else {
        location.href = "./dashboard.php";
      }
    },
  });
});
