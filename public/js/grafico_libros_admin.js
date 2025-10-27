fetch("../../controllers/datos_grafico_libros_admin.php")
  .then((Response) => {
    return Response.json();
  })
  .then((result) => {
    const labels = result.map((item) => item.titulo);
    const valores = result.map((item) => item.cantidad);

    const colores = [
      "rgba(255, 99, 132, 0.5)",
      "rgba(54, 162, 235, 0.5)",
      "rgba(255, 206, 86, 0.5)",
      "rgba(75, 192, 192, 0.5)",
      "rgba(153, 102, 255, 0.5)",
      "rgba(255, 159, 64, 0.5)",
    ];

    const ctx = document.getElementById("graficoLibros").getContext("2d");
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Libros más prestados",
            data: valores,
            backgroundColor: colores,
            borderColor: colores.map((c) => c.replace("0.5", "1")), // borde más fuerte
            borderWith: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  })
  .catch((error) => console.error("Error al cargar los datos: ", error));
