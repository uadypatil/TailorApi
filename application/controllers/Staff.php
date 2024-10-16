<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Staff extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("StaffModel");
        $this->load->model("AdminModel");
    }

    function GetAllStaffs()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $staffs = $this->StaffModel->getStaffs();
            if ($staffs!=null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $staffs, "message" => "Staff Fetched Successfully");
            } else {
                $this->output->set_status_header(404);

                $response = array("status" => "error", "message" => "No Staff Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
  
    function AddStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $password = $this->MainModel->encryptData($formdata["password"]);
            $formdata["password"] = $password;
            $result = $this->StaffModel->registerStaff($formdata);
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Staff Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Adding Staff");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetStaff($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Staff = $this->StaffModel->getStaff($id);
            if ($Staff != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Staff, "message" => "Staff Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Staff Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateStaff($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Staff = $this->StaffModel->getStaff($id);
            if ($Staff != null) {
                    $formdata = $this->input->post();
                    $password = $this->MainModel->encryptData($formdata["password"]);
                    $formdata["password"] = $password;
                    $result = $this->StaffModel->updateStaff($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Staff Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating Staff Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Staff Found");
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
                $result=$this->StaffModel->updatePassword($id,$password);
                if($result==true)
                {
                    $this->output->set_status_header(200);
                    $response = array("status" => "success","message" => "Staff Password Updated Successfully");
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

    function DeleteStaff($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Staff = $this->StaffModel->getStaff($id);
            if ($Staff != null) {
                    $result = $this->StaffModel->deleteStaff($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Staff Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Staff Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Staff Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
}
