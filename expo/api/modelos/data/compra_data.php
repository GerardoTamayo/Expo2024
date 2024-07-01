<?php
// Se incluye la clase para validar los datos de entrada.
require_once ('../../ayudantes/validar.php');
// Se incluye la clase padre.
require_once ('../../modelos/handler/compra_handler.php');
/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla pedidos.
 */
class CompraData extends CompraHandler
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
            $this->id_compra = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del pedido es incorrecto';
            return false;
        }
    }


    // Validación y asignación del estado del pedido.
    public function setEstado($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estado_venta = $value;
            return true;
        } else {
            $this->data_error = 'El estado es incorrecto';
            return false;
        }
           
    }

    /*
     *   Métodos para validar y establecer los datos.
     */
    public function setIdProveedor($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_proveedor = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del proveedor es incorrecto';
            return false;
        }
    }

    public function setCorrelativo($value)
    {
            $this->numero_correlativo = $value;
            return true;
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha_compra = $value;
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