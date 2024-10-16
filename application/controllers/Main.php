<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");   
    }

  
    function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $username=$this->input->post("username");
            $password=$this->MainModel->encryptData($this->input->post("password"));
            $role=$this->input->post("role");
            $user=$this->MainModel->loginValidation($username,$password,$role);
            if ($user!=null) {
                $this->output->set_status_header(200);
                $response = array("status" => "Success","data"=>$user,"message" => "Login Successfull");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "Error","message" => "Wrong Credentials");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    

    // function Register()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === "POST") {
    //        $formdata=$this->input->post();
    //         $role=$this->input->post("role");
    //         // $user=$this->MainModel->loginValidation($username,$password,$role);
    //         if ($user!=null) {
    //             $this->output->set_status_header(200);
    //             $response = array("status" => "Success","data"=>$user,"message" => "Login Successfull");
    //         } else {
    //             $this->output->set_status_header(404);
    //             $response = array("status" => "Error","message" => "Wrong Credentials");
    //         }
    //     } else {
    //         $this->output->set_status_header(405);
    //         $response = array("status" => "error", "message" => "Bad Request");
    //     }
    //     $this->output->set_content_type("application/json")->set_output(json_encode($response));
    // }
}

?>