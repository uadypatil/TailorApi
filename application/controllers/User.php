<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

// url: https://localhost/TailorApi/User/
class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");
        $this->load->model("UserModel");

        // auto create admin user
        $this->MainModel->AutoCreateAdmin();

        // php mailer library        
        $this->load->library('phpmailer_lib');
    }

    /////////////////////////////////////////////////////////////////   user profile
    // Function to get user profile data
    function getUserProfileData($id)
    {
        $data = $this->UserModel->getUserProfileData($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data);
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "User not found");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to update user authentication data
    function updateUserAuthData($authId)
    {
        $data = $this->input->post();
        $status = $this->UserModel->updateUserAuthData($authId, $data);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "User authentication updated");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to update authentication data");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to update user data
    function updateUserData($userId)
    {
        $formdata = $this->input->post();

        $config['upload_path'] = 'uploads/user/profile/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 5120; // 5 MB in KB
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $data = []; // Initialize an array to hold the response data

        // Check if a file is uploaded

        if (isset($_FILES["document"])) {
            if (!$this->upload->do_upload('document')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $formdata['profilepic'] = $this->upload->data()['file_name'];
            }
        }

        $status = $this->UserModel->updateUserData($userId, $formdata);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "message" => "User data updated");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "error", "message" => "Failed to update user data");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to update password
    function updateUserPassword($authId)
    {
        $oldPass = $this->input->post('currentpassword');
        $newPass = $this->input->post('newpassword');

        $status = $this->UserModel->updateUserPassword($authId, $oldPass, $newPass);
        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "Password updated");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to update password");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends    

    ///////////////////////////////////////////////////////////////////     Tailor profile data
    // Function to get experienced tailor data
    function getExperiencedTailorData()
    {
        $data = $this->UserModel->getExperiencedTailorData();

        if ($data) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "data" => $data);
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "No experienced tailors found");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to get tailor data by ID
    function getTailorDataById($tailorId)
    {
        $data = $this->UserModel->getTailorDataById($tailorId);
        $response = ($data) ?
            array("status" => "Success", "data" => $data) :
            array("status" => "Error", "message" => "Tailor not found");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to get FAQs for a tailor
    function getTailorFaqsByTailorId($tailorId)
    {
        $data = $this->UserModel->getTailorFaqsByTailorId($tailorId);
        $response = ($data) ?
            array("status" => "Success", "data" => $data) :
            array("status" => "Error", "message" => "No FAQs found for this tailor");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to get tailor reviews
    function getTailorReviewsByTailorId($tailorId)
    {
        $data = $this->UserModel->getTailorReviewsByTailorId($tailorId);
        $response = ($data) ?
            array("status" => "Success", "data" => $data) :
            array("status" => "Error", "message" => "No reviews found for this tailor");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    ////////////////////////////////////////////////////////////////////        appointments
    // Function to get appointments by user ID
    function getAppointmentsByUserId($userId)
    {
        $data = $this->UserModel->getAppointmentsByUserId($userId);
        $response = ($data) ?
            array("status" => "success", "data" => $data) :
            array("status" => "Error", "message" => "No appointments found");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to book an appointment
    function bookAppointment()
    {
        $data = $this->input->post();
        $status = $this->UserModel->bookAppointment($data);
        $response = ($status) ?
            array("status" => "Success", "message" => "Appointment booked successfully") :
            array("status" => "Error", "message" => "Failed to book appointment");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to get an appointment by ID
    function getAppointmentById($id)
    {
        $data = $this->UserModel->getAppintmentById($id);
        $response = ($data) ?
            array("status" => "Success", "data" => $data) :
            array("status" => "Error", "message" => "Appointment not found");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // Function to update an appointment
    function updateAppointment($id)
    {
        $data = $this->input->post();
        $status = $this->UserModel->updateAppintment($id, $data);

        if ($status) {
            $this->output->set_status_header(200);
            $response = array("status" => "Success", "message" => "Appointment updated");
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "Error", "message" => "Failed to update appointment");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    //////////////////////////////////////////////////////////////////////      feedback form
    // Function to add feedback
    function addFeedbackPost()
    {
        $data = $this->input->post();
        $status = $this->UserModel->addFeedbackPost($data);
        $response = ($status) ?
            array("status" => "Success", "message" => "Feedback submitted successfully") :
            array("status" => "Error", "message" => "Failed to submit feedback");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends    

    // function to get tailors by location and service
    function getTailorByLocationService()
    {
        $formdata = $this->input->post();
        $location = $formdata['location'];
        $service = $formdata['service'];
        $data = $this->UserModel->getTailorByLocationService($location, $service);
        $response = ($data) ?
            array("status" => "Success", "message" => "Data Fetched successfully", "data" => $data) :
            array("status" => "Error", "message" => "Failed to fetch data", "data" => null);
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends

    // function to get location for search 
    function getLocationsForSearch(){
        $data = $this->UserModel->getLocationsForSearch();
        $response = ($data) ?
            array("status" => "success", "data" => $data) :
            array("status" => "Error", "message" => "No appointments found");
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }   // function ends    
}
