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
        $response = array("status" => "success", "tailor_count" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get total user count
    function getTotalUserCount()
    {
        $count = $this->AdminModel->getTotalUserCount();
        $response = array("status" => "success", "user_count" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get total total tailor pending requests
    function getTailorPendingRequestCount()
    {
        $count = $this->AdminModel->getTailorPendingRequestCount();
        $response = array("status" => "success", "pending_tailor_count" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to fetch approved tailor list
    function getApprovedTailorList()
    {
        $data = $this->AdminModel->getApprovedTailorList();
        if ($data) {
            $response = array("status" => "success", "tailors" => $data);
        } else {
            $response = array("status" => "error", "message" => "No approved tailors found.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get tailor pending request records
    function getTailorPendingRequests()
    {
        $data = $this->AdminModel->getTailorPendingRequests();
        if ($data) {
            $response = array("status" => "success", "pending_tailors" => $data);
        } else {
            $response = array("status" => "error", "message" => "No pending tailor requests found.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    //  function to get admin  profile data
    function getAdminProfileData()
    {
        $data = $this->AdminModel->getAdminProfileData();
        if ($data) {
            $response = array("status" => "success", "admin_data" => $data);
        } else {
            $response = array("status" => "error", "message" => "Admin profile data not found.");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to update admin password
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








}
