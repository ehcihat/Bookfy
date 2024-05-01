<?php

class Response
{

    public $response = [
        'status' => "OK",
        "result" => array()

    ];
    /**
     * Devuelve una respuesta de error 405.
     *
     * @return array El mensaje de error.
     */
    public function error_405()
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "405",
            "Error_msg" => "Method not allowed"
        );

        return $this->response;
    }
//Se realiza lo mismo para los distintos errores.
    public function error_406($value = "Invalid data provided.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "406",
            "Error_msg" => $value
        );

        return $this->response;
    }



    public function error_408($value = "Email does not exist.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "408",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_409($value = "Incorrect password.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "409",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_414($value = "Text too long.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "414",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_415($value = "Please provide a valid price.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "415",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_416($value = "ISBN already exists.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "416",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_400()
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "400",
            "Error_msg" => "The request is not well-formed."
        );
        return $this->response;
    }
    public function error_401($value = "Unauthorized.")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "401",
            "Error_msg" => $value
        );

        return $this->response;
    }

    public function error_500()
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "500",
            "Error_msg" => "Internal server error."
        );
        return $this->response;
    }


}