<?php
defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Librarian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");
        $this->load->model("LibrarianModel");
    }

    // function to get all Librarian records

    function GetAllLibrarians()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Librarians = $this->LibrarianModel->getLibrarians();
            if ($Librarians != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Librarians, "message" => "Librarians Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Librarians Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    
    function RegisterLibrarian()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $result = $this->LibrarianModel->addLibrarian($formdata);
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Librarian Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Adding Librarian");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetLibrarian($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Librarian = $this->LibrarianModel->getLibrarianById($id);
            if ($Librarian != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Librarian, "message" => "Librarian Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Librarian Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateLibrarian($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Librarian = $this->LibrarianModel->getLibrarianById($id);
            if ($Librarian != null) {
                    $formdata = $this->input->post();
                    $result = $this->LibrarianModel->updateLibrarian($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Librarian Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating Librarian Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Librarian Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function DeleteLibrarian($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Librarian = $this->LibrarianModel->getLibrarianById($id);
            if ($Librarian != null) {
                    $result = $this->LibrarianModel->deleteLibrarian($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Librarian Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Librarian Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Librarian Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }


}

