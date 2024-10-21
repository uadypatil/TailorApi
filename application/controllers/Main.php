<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("MainModel");
    }


    function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $username = $this->input->post("username");
            $password = $this->MainModel->encryptData($this->input->post("password"));
            $role = $this->input->post("role");
            $user = $this->MainModel->loginValidation($username, $password, $role);
            if ($user != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "Success", "data" => $user, "message" => "Login Successfull");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "Error", "message" => "Wrong Credentials");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    // function for registration
    function registrationPost()
    {
        $returned = "";
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $formdata = $this->input->post();
            $formdata['password'] = $this->MainModel->encryptData($formdata["password"]);

            $authenticationdata = array(
                'email' => $formdata['email'],
                'contactno' => $formdata['contactno'],
                'password' => $formdata['password'],
                'type' => $formdata['type'],
            );

            $authid = $this->MainModel->AuthenticationPost($authenticationdata);
            if ($authid != null) {
                if ($authid == "contactno exist") {
                    $this->output->set_status_header(404);
                    $response = array("status" => "Error", "message" => "Contact Number Already Exist");
                    $returned  = -1;
                } else if ($authid == "email exist") {
                    $this->output->set_status_header(404);
                    $returned = -2;
                } else {

                    if ($formdata['type'] == "tailor") {
                        // adding tailor data to array
                        $tailordata = array(
                            "authid" => $authid,
                            "document_type" => $formdata['document_type'],
                            "document" => $formdata['document'],
                            "shop_license" => $formdata['shop_license'],
                            "address" => $formdata['address']
                        );

                        $config['upload_path'] = 'uploads/tailor/documents';
                        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf'; // Adjust as needed
                        $config['max_size'] = 102400;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (isset($_FILES["document"])) {
                            if (!$this->upload->do_upload('document')) {
                                $data['error'] = $this->upload->display_errors();
                                // print_r($data);
                            } else {
                                $data['upload_data'] = $this->upload->data();
                                $tailordata['document'] = $data['upload_data']['file_name'];
                            }
                        }

                        $returned = $this->MainModel->setTailorDataPost($tailordata);
                    } else if ($formdata['type'] == "user") {
                        // adding userdata to array
                        $userdata = array(
                            "authid" => $authid,
                            "name" => $formdata['name'],
                            "address" => $formdata['address'],
                        );

                        $returned = $this->MainModel->setUserDataPost($userdata);
                    }
                }
            }

            // $user = $this->MainModel->loginValidation($username, $password, $role);
            if($returned === -1){
                $response = array("status" => "Error", "message" => "Contact number already exist");
            }else if($returned === -2){
                $response = array("status" => "Error", "message" => "Email already exist");
            }else if ($returned != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "Success", "message" => "Registration Successfull");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "Error", "message" => "Failed to Register " . $authid ." | " . $returned);
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
}
