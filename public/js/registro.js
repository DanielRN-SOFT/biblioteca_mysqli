function cargandoAlerta(mensaje) {
  Swal.fire({
    title: mensaje,
    text: "Por favor espere un momento.",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

let btnRegistrar = document.querySelector("#btn-registrarse");
btnRegistrar.addEventListener("click", async (e) => {
  e.preventDefault();

  const form = document.querySelector("#frmRegistrarse");
  const formData = new FormData(form);
  cargandoAlerta("Registrando Usuario...");
  const response = await fetch("../../controllers/login_registro.php", {
    method: "POST",
    body: formData,
  });

  const result = await response.json();
  console.log(result);

  if (!result.success) {
    Swal.fire({
      title: '<span class="fs-2 fw-bold"> Error </span>',
      text: result.message,
      icon: "error",
    });
  } else {
    Swal.fire({
      title: '<span class="fs-2 fw-bold"> ¡Exito! </span>',
      text: result.message,
      icon: "success",
    }).then(() => {
      location.href = "./login.php";
    });
  }
});
