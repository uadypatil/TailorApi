<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("AdminModel");
        $this->load->model("MainModel");
        date_default_timezone_set('Asia/Kolkata');
  
    }

    function RegisterAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $result=$this->AdminModel->createAdmin();
            if ($result=="Already Exists") {
                $this->output->set_status_header(409);
                $response = array("status" => "Error","message" => "Admin Already Exists");
            } else {
                $this->output->set_status_header(200);
                $response = array("status" => "Success","message" => "Admin Created Successfully");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata=$this->input->post();
            if(isset($formdata["password"]))
            {
                $password=$this->MainModel->encryptData($formdata["password"]);
                $formdata["password"]=$password;
                $result=$this->AdminModel->updatePassword($password);
                if($result==true)
                {
                    $this->output->set_status_header(200);
                    $response = array("status" => "success","message" => "Admin Data Updated Successfully");
                }
                else{
                    $this->output->set_status_header(500);
                    $response = array("status" => "error","message" => "Some Error Occured While Updating Admin Data"); 
                }
            }
            else {
                $this->output->set_status_header(400);
                $response = array("status" => "error", "message" => "Password is not set");
            }
        } 
        else {
            $this->output->set_status_header(400);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
}
