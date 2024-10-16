<?php


defined("BASEPATH") or exit("No direct Script Access Allowed");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers:*");

class Student extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("StudentModel");
        $this->load->model("AdminModel");
    }

    function GetAllStudents()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Students = $this->StudentModel->getStudents();
            if ($Students!=null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Students, "message" => "Students Fetched Successfully");
            } else {
                $this->output->set_status_header(404);

                $response = array("status" => "error", "message" => "No Students Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function AddStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $password = $this->MainModel->encryptData($formdata["password"]);
            $formdata["password"] = $password;

            $config['upload_path'] = 'uploads/Student';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf'; // Adjust as needed
            $config['max_size'] = 102400;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (isset($_FILES["student_img"])) {
                if (!$this->upload->do_upload('student_img')) {
                    $data['error'] = $this->upload->display_errors();
                    print_r($data);
                } else {
                    $data['upload_data'] = $this->upload->data();
                    $formdata['student_img'] = $data['upload_data']['file_name'];
                }
            }
            
            $result = $this->StudentModel->addStudent($formdata);
            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Student Registered Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Registering Student");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetStudent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Student = $this->StudentModel->getStudent($id);
            if ($Student != null) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "data" => $Student, "message" => "Student Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Student Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateStudent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();

            $config['upload_path'] = 'uploads/Student';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf'; // Adjust as needed
            $config['max_size'] = 102400;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (isset($_FILES["student_img"])) {
                if (!$this->upload->do_upload('student_img')) {
                    $data['error'] = $this->upload->display_errors();
                    print_r($data);
                } else {
                    $data['upload_data'] = $this->upload->data();
                    $formdata['student_img'] = $data['upload_data']['file_name'];
                }
            }
            
                    $password = $this->MainModel->encryptData($formdata["password"]);
                    $formdata["password"] = $password;
                    $result = $this->StudentModel->updateStudent($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200 );
                        $response = array("status" => "success", "data" => $result, "message" => "Student Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating Student Data");
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
                $result=$this->StudentModel->updatePassword($id,$password);
                if($result==true)
                {
                    $this->output->set_status_header(200);
                    $response = array("status" => "success","message" => "Student Password Updated Successfully");
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

    function DeleteStudent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Student = $this->StudentModel->getStudent($id);
            if ($Student != null) {
                    $result = $this->StudentModel->deleteStudent($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "data" => $result, "message" => "Student Data Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Student Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Student Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetStudentId($studentid)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Student = $this->StudentModel->getStudentId($studentid);
            if ($Student != null) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "data" => $Student, "message" => "Student Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Student Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetStudentContact($contact)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $contact = urldecode($contact);
            $Student = $this->StudentModel->getStudentContact($contact);
            if ($Student != null) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "data" => $Student, "message" => "Student Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Student Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetStudentEmail($email)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $email = urldecode($email);
            $Student = $this->StudentModel->getStudentEmail($email);
            if ($Student != null) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "data" => $Student, "message" => "Student Data Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Student Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    
}
?>