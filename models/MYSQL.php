<?php
// Clase para gestionar la conexión a la base de datos
class MySQL
{

    // Datos de conexión
     private $ipServidor = "bts4vahkrnelqbarqbkj-mysql.services.clever-cloud.com";
     private $usuarioBase = "ueidhxxqptq4fxou";
     private $contrasena = "UxxjUzE9KHlkkZBAupKi";
     private $nombreBaseDatos = "bts4vahkrnelqbarqbkj";

    // private $ipServidor = "localhost";
    // private $usuarioBase = "root";
    // private $contrasena = "";
    // private $nombreBaseDatos = "biblioteca_prueba"; 
 
    private $conexion;

    // Método para conectar a la base de datos
    public function conectar()
    {
        $this->conexion = mysqli_connect($this->ipServidor, $this->usuarioBase, $this->contrasena, $this->nombreBaseDatos);

        // Validar si hubo un error en la conexión
        if (!$this->conexion) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }

        // Establecer codificación utf8
        mysqli_set_charset($this->conexion, "utf8");
    }

    // Método para desconectar la base de datos
    public function desconectar()
    {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }

    // Método para ejecutar una consulta y devolver su resultado
    public function efectuarConsulta($consulta)
    {
        // Verificar que la codificación sea utf8 antes de ejecutar
        mysqli_query($this->conexion, "SET NAMES 'utf8'");
        mysqli_query($this->conexion, "SET CHARACTER SET 'utf8'");

        $resultado = mysqli_query($this->conexion, $consulta);

        if (!$resultado) {
            echo "Error en la consulta: " . mysqli_error($this->conexion);
        }

        return $resultado;
    }

    public function ultimoID()
    {
        return mysqli_insert_id($this->conexion);
    }

    public function comenzarTransaccion()
    {
        mysqli_begin_transaction($this->conexion);
    }

    public function confirmarTransaccion()
    {
        mysqli_commit($this->conexion);
    }

    public function cancelarTransaccion()
    {
        mysqli_rollback($this->conexion);
    }
}
