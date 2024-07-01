<?php
// Se incluye la clase para validar los datos de entrada.
require_once ('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once ('../../modelos/handler/venta_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla pedidos.
 */
class VentaData extends VentaHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;
    // Atributo para almacenar el nombre del archivo de imagen.
    private $filename = null;
    /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    // Validación y asignación del ID de Pedido.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_venta = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del pedido es incorrecto';
            return false;
        }
    }


    // Validación y asignación del estado del pedido.
    public function setEstado($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->estado_venta = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    /*
     *   Métodos para validar y establecer los datos.
     */
    public function setIdProveedor($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del proveedor es incorrecto';
            return false;
        }
    }


    public function setObservacion($value, $min = 2, $max = 1000)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'La observacion contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->observacion_venta = $value;
            return true;
        } else {
            $this->data_error = 'La observacion debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_venta = $value;
            return true;
        } else {
            $this->data_error = 'Formato incorrecto';
            return false;
        }
    }

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

    // Método para obtener el nombre del archivo de imagen.
    public function getFilename()
    {
        return $this->filename;
    }

}