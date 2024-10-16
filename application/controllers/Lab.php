<?php
defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Lab extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");
        $this->load->model("LabModel");
    }

    // function to get all lab records

    function GetAllLabs()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $labs = $this->LabModel->getLabs();
            if ($labs != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $labs, "message" => "Labs Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Labs Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    
    function RegisterLab()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $result = $this->LabModel->addLab($formdata);
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Lab Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Adding Lab");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetLab($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $lab = $this->LabModel->getLabById($id);
            if ($lab != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $lab, "message" => "Lab Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Lab Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateLab($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lab = $this->LabModel->getLabById($id);
            if ($lab != null) {
                    $formdata = $this->input->post();
                    $result = $this->LabModel->updateLab($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Lab Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating Lab Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Lab Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function DeleteLab($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lab = $this->LabModel->getLabById($id);
            if ($lab != null) {
                    $result = $this->LabModel->deleteLab($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Lab Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Lab Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Lab Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }


}

