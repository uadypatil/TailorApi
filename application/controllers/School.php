<?php
defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class School extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("SchoolModel");
        $this->load->model("MainModel");
    }

    function GetAllSchools()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $schools = $this->SchoolModel->getSchools();
            if ($schools != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $schools, "message" => "Schools Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Schools Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    
    function AddSchool()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $password = $this->MainModel->encryptData($formdata["password"]);
            $formdata["password"] = $password;
            $result = $this->SchoolModel->registerSchool($formdata);
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "School Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Adding School");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetSchool($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $school = $this->SchoolModel->getSchool($id);
            if ($school != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $school, "message" => "School Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No School Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateSchool($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $school = $this->SchoolModel->getSchool($id);
            if ($school != null) {
                    $formdata = $this->input->post();
                    $password = $this->MainModel->encryptData($formdata["password"]);
                    $formdata["password"] = $password;
                    $result = $this->SchoolModel->updateSchool($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "School Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating School Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No School Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdatePassword($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata=$this->input->post();
            if(isset($formdata["password"]))
            {
                $password=$this->MainModel->encryptData($formdata["password"]);
                $result=$this->SchoolModel->updatePassword($id,$password);
                if($result==true)
                {
                    $this->output->set_status_header(200);
                    $response = array("status" => "success","message" => "School Password Successfully");
                }
                else{
                    $this->output->set_status_header(500);
                    $response = array("status" => "error","message" => "Some Error Occured While Updating Password"); 
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

    function DeleteSchool($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $school = $this->SchoolModel->getSchool($id);
            if ($school != null) {
                    $result = $this->SchoolModel->deleteSchool($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "School Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting School Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No School Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
}
