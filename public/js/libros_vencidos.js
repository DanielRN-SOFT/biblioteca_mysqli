document.addEventListener("DOMContentLoaded", () => {
  fetch("../../controllers/validar_prestamos_vencidos.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success && data.libros.length > 0) {
        let mensaje = `Por favor devuelve los siguientes libros: ${data.libros.join(", ")}`;
        let mostrarBoton = data.total > data.libros.length;

        if (mostrarBoton) {
          mensaje += ` y ${data.total - data.libros.length} más...`;
        }

        Swal.fire({
          icon: "warning",
          title: "¡Tienes prestamos vencidos!",
          text: mensaje,
          showCancelButton: mostrarBoton,
          confirmButtonText: "Aceptar",
          cancelButtonText: "Ver todos",
        }).then((resultado) => {
          // Si el usuario presiona "Ver todos"
          if (resultado.dismiss === Swal.DismissReason.cancel) {
            fetch("../../controllers/validar_prestamos_vencidos.php?all=true")
              .then((response) => response.json())
              .then((dataFull) => {
                if (dataFull.success && dataFull.libros.length > 0) {
                  Swal.fire({
                    icon: "info",
                    title: "Todos tus libros vencidos",
                    html: `<ul style="text-align:left">${dataFull.libros
                      .map((libro) => `<li>${libro}</li>`)
                      .join("")}</ul>`,
                    confirmButtonText: "Aceptar",
                  });
                }
              });
          }
        });
      }
    })
    .catch((error) => console.error("Error:", error));
});
