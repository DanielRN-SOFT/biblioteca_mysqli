// ACTUALIZAR PRESTAMO
document.querySelectorAll(".btn-actualizar-prestamo").forEach((boton) => { //el foreach recorre todos los botones con la clase .btn-actualizar-prestamo y los agrega a la variable boton
  boton.addEventListener("click", () => {
    let id = boton.dataset.id; //se obtiene el id de cada préstamo
    // Mostrar alerta de confirmación
    Swal.fire({
      title: "¿Actualizar préstamo?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, actualizar",
      cancelButtonText: "Cancelar",
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../controllers/actualizarPrestamo.php",
          type: "POST",
          data: { idPrestamo: id },
          dataType: "json",
          success: function (response) {
            Swal.fire({
              title: "Hecho",
              text: response.message,
              icon: response.success ? "success" : "error",
              timer: 1000,
              showConfirmButton: false,
            }).then(() => {
              location.reload();
            });
          },
        });
      }
    });
  });
});
