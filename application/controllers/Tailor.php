<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

// url: https://localhost/TailorApi/Tailor/
class Tailor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("TailorModel");
        $this->load->model("TailorModel");

        // auto create admin user
        $this->MainModel->AutoCreateAdmin();

        // php mailer library        
        $this->load->library('phpmailer_lib');
    }

    // function to get tailor profile details
    function getTailorProfileDetails($id)
    {
        $data = $this->TailorModel->getTailorProfileDetailsById($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "data" => $data, "message" => "Profile details fetched successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "Tailor not found");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to update tailor authentication details
    function updateTailorAuthDetails($authId)
    {
        $data = $this->input->post();
        $this->TailorModel->updateTailorAuthDetailsByAuthId($authId, $data);
        $this->output->set_status_header(200);
        $response = array("status" => "Success", "message" => "Tailor authentication details updated");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to update tailor details
    function updateTailorDetails($id)
    {
        $data = $this->input->post();
        $this->TailorModel->updateTailorDetailsById($id, $data);
        $this->output->set_status_header(200);
        $response = array("status" => "Success", "message" => "Tailor details updated successfully");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to delete tailor
    function deleteTailor($id)
    {
        $status = $this->TailorModel->deleteTailorRecordByTailorId($id);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "Tailor record deleted successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "Failed to delete tailor record");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    //  function to get faqs by tailor id
    function getFaqsByTailorId($id)
    {
        $data = $this->TailorModel->getFaqByTailorId($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "data" => $data);
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "No FAQs found for this tailor");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to add faq
    function addFaq()
    {
        $data = $this->input->post();
        $status = $this->TailorModel->addFaq($data);
        if ($status) {
            $this->output->set_status_header(201);
            $response = array("status" => "Success", "message" => "FAQ added successfully");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to add FAQ");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // funciton to update faq
    function updateFaq($id)
    {
        $data = $this->input->post();
        $status = $this->TailorModel->updateFaqById($id, $data);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "FAQ updated successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "Failed to update FAQ");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to delete faq
    function deleteFaq($id)
    {
        $status = $this->TailorModel->deleteFaqById($id);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "FAQ deleted successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "Failed to delete FAQ");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to update password
    function updatePassword($authId)
    {
        $oldpass = $this->input->post('oldpass');
        $newpass = $this->input->post('newpass');
        $status = $this->TailorModel->updatePasswordByAuthId($authId, $oldpass, $newpass);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "Password updated successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "Failed to update password");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to contact to admin
    function contactAdmin()
    {
        $data = $this->input->post();
        $status = $this->TailorModel->addConactUsPost($data);
        if ($status) {
            $this->output->set_status_header(201);
            $response = array("status" => "Success", "message" => "Message sent to admin successfully");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to send message");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // functijon to get pending orders
    function getPendingOrdersCount($tailorId)
    {
        $count = $this->TailorModel->getCountPendingOrdersByTailorId($tailorId);
        $this->output->set_status_header(200);
        $response = array("status" => "Success", "count" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get approved orders count
    function getApprovedOrdersCount($tailorId)
    {
        $count = $this->TailorModel->getApprovedOrdersCountByTailorId($tailorId);
        $this->output->set_status_header(200);
        $response = array("status" => "Success", "data" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get completed orders count
    function getCompletedOrdersCount($tailorId)
    {
        $count = $this->TailorModel->getCompletedOrdersCountByTailorId($tailorId);
        $this->output->set_status_header(200);
        $response = array("status" => "Success", "data" => $count);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get appointments
    function getAppointments($tailorId)
    {
        $data = $this->TailorModel->getAppointmentsByTailorId($tailorId);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "data" => $data);
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "No appointments found");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get all reviews
    function getReviews($tailorId)
    {
        $data = $this->TailorModel->getAllReviewsByTailorId($tailorId);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "data" => $data);
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "Error", "message" => "No reviews found");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends










}
