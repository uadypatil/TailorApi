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

        $this->AdminModel->autocreateAdmin();
    }

    // function to get tailor count
    function getTotalTailorCount()
    {
        $count = $this->AdminModel->getTotalTailorCount();
        if ($count) {
            $data = array(
                "tailor_count" => $count->tailorcount
            );
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data);
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to fetch count");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get total user count
    function getTotalUserCount()
    {
        $count = $this->AdminModel->getTotalUserCount();

        if ($count) {
            $data = array(
                "user_count" => $count->usercount
            );
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data);
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to fetch count");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get total total tailor pending requests
    function getTailorPendingRequestCount()
    {
        $count = $this->AdminModel->getTailorPendingRequestCount();

        if ($count) {
            $data = array(
                "pending_request_count" => $count->tailorcount
            );
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data);
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to fetch count");
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends
    // Function to fetch approved tailor list
    function getApprovedTailorList()
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $data = $this->AdminModel->getApprovedTailorList();
            if ($data) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data);
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No approved tailors found.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    // Function to get tailor pending request records
    function getTailorPendingRequests()
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $data = $this->AdminModel->getTailorPendingRequests();
            if ($data) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data);
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No pending tailor requests found.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    // Function to get admin profile data
    function getAdminProfileData()
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $data = $this->AdminModel->getAdminProfileData();
            if ($data) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data);
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "Admin profile data not found.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }



    // function to update admin password'
    function updateAdminPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata = $this->input->post();
            if (isset($formdata["old_password"]) && isset($formdata["new_password"])) {
                $oldpass = $this->AdminModel->encryptData($formdata["old_password"]);
                $newpass = $this->AdminModel->encryptData($formdata["new_password"]);

                $result = $this->AdminModel->updateAdminPassword($oldpass, $newpass);
                if ($result) {
                    $this->output->set_status_header(200);
                    $response = array("status" => "success", "message" => "Password updated successfully.");
                } else {
                    $this->output->set_status_header(400);
                    $response = array("status" => "error", "message" => "Invalid old password.");
                }
            } else {
                $this->output->set_status_header(400);
                $response = array("status" => "error", "message" => "Old and new passwords must be provided.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to toggle tailors request at admin side
    function ToggleTailorRequest($tailorid, $action)
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $result = $this->AdminModel->ToggleTailorRequest($tailorid, $action);
            if ($result) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "message" => "Status is updated.");
            } else {
                $this->output->set_status_header(400);
                $response = array("status" => "error", "message" => "Failed to update status.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get contact data
    function getContactData(){
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $data = $this->AdminModel->getContactData();
            if ($data) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data);
            } else {
                $this->output->set_status_header(400);
                $response = array("status" => "error", "message" => "Failed to update status.");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Invalid request method.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends




}
