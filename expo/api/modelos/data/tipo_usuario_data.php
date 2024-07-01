<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once('../../modelos/handler/tipo_usuario_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla USUARIO.
 */
class TipoUsuariosData extends TiposUsuariosHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;

    /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    // Validación y asignación del ID del tipo de usuario.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_tipo = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del tipo de usuario es incorrecto';
            return false;
        }
    }

    // Validación y asignación nombre del tipo de usuario.
    public function setTipo($value, $min = 2, $max = 60)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El Tipo de usuario debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->tipo_usuario = $value;
            return true;
        } else {
            $this->data_error = 'El Tipo de usuario debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }
}
