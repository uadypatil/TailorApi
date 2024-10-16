<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");
class ClassController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();   
        $this->load->model("ClassModel");   
        $this->load->model("AdminModel");   
    }

    function GetAllClasses()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Classs = $this->ClassModel->getClasses();
            if ($Classs!=null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Classs, "message" => "Classes Fetched Successfully");
            } else {
                $this->output->set_status_header(404);

                $response = array("status" => "error", "message" => "No Classes Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function AddClass()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $id=$this->ClassModel->get_Last_Value_in_class_id();
            if($id==null)
            {
                $id="CLS-1";
            }
            else{
                $formdata["classid"]=$id;
            }
            if (isset($formdata['division'])) {
                $divisions = explode(',', $formdata['division']);
            }
            if (isset($formdata['teacherid'])) {
                $teachers= explode(',', $formdata['teacherid']);
            }
            for($i=0;$i<count($teachers);$i++)
            {
                $formdata["division"]=$teachers[$i];
                $formdata["teacherid"]=$divisions[$i];
                $result = $this->ClassModel->registerClass($formdata);
            }
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Class Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Registering Class");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetClass($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Class = $this->ClassModel->getClass($id);
            if ($Class != null) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "data" => $Class, "message" => "Class Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Class Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateClass($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Class = $this->ClassModel->getClass($id);
            if ($Class != null) {
                    $formdata = $this->input->post();
                    $result = $this->ClassModel->updateClass($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200 );
                        $response = array("status" => "success", "data" => $result, "message" => "Class Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating Class Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Class Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function DeleteClass($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Class = $this->ClassModel->getClass($id);
            if ($Class != null) {
                    $result = $this->ClassModel->deleteClass($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Class Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Class Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Class Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
}


?>