<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class Teacher extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("TeacherModel");
      
    }

    function UploadSyllabus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();
            $config['upload_path'] = 'uploads/Syllabus';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 102400;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $check=$this->TeacherModel->checkSyllabus($formdata);
            if($check!=null)
            {
                $this->output->set_status_header(409);
                $response = array("status" => "error", "message" => "Syllabus already exists");
            }
            else{
                if (isset($_FILES["syllabus"])) {
                    if (!$this->upload->do_upload('syllabus')) {
                        $data['error'] = $this->upload->display_errors();
                        print_r($data);
                    } else {
                        $data['upload_data'] = $this->upload->data();
                        $formdata['syllabus'] = $data['upload_data']['file_name'];
                    }
                }
                $result = $this->TeacherModel->uploadSyllabus($formdata);

                if ($result == true) {
                    $this->output->set_status_header(201);
                    $response = array("status" => "success", "message" => "Syllabus Uploaded Successfully");
                } else {
                    $this->output->set_status_header(500);
                    $response = array("status" => "error", "message" => "Some Error Occured While Uploading Syllabus");
                }

            }
          
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetAllLearningMaterials()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $Data = $this->TeacherModel->getLearningMaterials();
            if ($Data != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $Data, "message" => "Learning Material Fetched Successfully");
            } else {
                $this->output->set_status_header(404);

                $response = array("status" => "error", "message" => "No Data Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetLearningMaterial($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = $this->TeacherModel->getLearningMaterial($id);
            if ($data != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data, "message" => "Learning Material Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Data Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }
    function GetSyllabus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = $this->TeacherModel->getSyllabus();
            if ($data != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $data, "message" => "Syllabus Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Data Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function EditSyllabus($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->editSyllabus($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Classwork Get Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While get classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function UpdateSyllabus()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $id = $formdata['id'];
        unset($formdata['id']);
        $config['upload_path'] = 'uploads/Syllabus';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 102400;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (isset($_FILES["syllabus"])) {
            if (!$this->upload->do_upload('syllabus')) {
                $data['error'] = $this->upload->display_errors();
                print_r($data);
            } else {
                $data['upload_data'] = $this->upload->data();
                $formdata['syllabus'] = $data['upload_data']['file_name'];
            }
        }
        $result = $this->TeacherModel->updateSyllabus($id, $formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Classwork Updated Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Updating classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}



    function AddLearningMaterial()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata=$this->input->post();
            $config['upload_path'] = 'uploads/learningmaterial/';
            $config['allowed_types'] = 'pdf|mp4|avi|mov|mkv';
            $config['max_size'] = 102400;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
  
                if (!$this->upload->do_upload('file')) {
                    $data = $this->upload->display_errors();
                    print_r($data);
                } else {
                    $data['upload_data'] = $this->upload->data();
                    $formdata['file'] = $data['upload_data']['file_name'];
                }

            $result = $this->TeacherModel->uploadLearningMaterial($formdata);
            if ($result) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "message" => "Learning material Uploaded Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "Error Uploading Learning Material");
            }
        } else {
            $this->output->set_status_header(405);
            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }


 


    // function UpdateLearningMaterial($id)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $config['upload_path'] = 'uploads/learningmaterial/';
    //         $config['allowed_types'] = 'pdf|mp4|avi|mov|mkv';
    //         $config['max_size'] = 102400;
    //         $this->load->library('upload', $config);
    //         $this->upload->initialize($config);
    //         $formdata=$this->input->post();
    //         if (isset($_FILES["file"])) {
    //             if (!$this->upload->do_upload('file')) {
    //                 $data = $this->upload->display_errors();
    //                 print_r($data);
    //             } else {
    //                 $data['upload_data'] = $this->upload->data();
    //                 $formdata['file'] = $data['upload_data']['file_name'];
    //             }
    //         }
    //         $result=$this->TeacherModel->updateLearningMaterial($id,$formdata);
    //         if ($result) {
    //             $this->output->set_status_header(200);
    //             $response = array("status" => "success", "message" => "Learning material Cannot be Updated");
    //         } else {
    //             $this->output->set_status_header(404);
    //             $response = array("status" => "error", "message" => "No Data Found");
    //         }
    //     } else {
    //         $this->output->set_status_header(405);

    //         $response = array("status" => "error", "message" => "Bad Request");
    //     }
    //     $this->output->set_content_type("application/json")->set_output(json_encode($response));
    // }

    // function DeleteLearningMaterial()
    // {

    // }




    // Assignment
function GetAllAssignments()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllAssignments();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Assignments Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function GetAssignment($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAssignment($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Assignment Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


function AddAssignment()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $config['upload_path'] = 'uploads/Assignment';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 102400;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
            if (isset($_FILES["assignment"])) {
                if (!$this->upload->do_upload('assignment')) {
                    $data['error'] = $this->upload->display_errors();
                    print_r($data);
                } else {
                    $data['upload_data'] = $this->upload->data();
                    $formdata['assignment'] = $data['upload_data']['file_name'];
                }
            }
            $result = $this->TeacherModel->addAssignment($formdata);

            if ($result == true) {
                $this->output->set_status_header(201);
                $response = array("status" => "success", "message" => "Assignment Uploaded Successfully");
            } else {
                $this->output->set_status_header(500);
                $response = array("status" => "error", "message" => "Some Error Occured While Uploading assignment");
            }

        
      
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function UpdateAssignmentPost($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formdata = $this->input->post();

            $config['upload_path'] = 'uploads/Assignment';
            $config['allowed_types'] = 'pdf'; // Adjust as needed
            $config['max_size'] = 102400;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (isset($_FILES["assignment"])) {
                if (!$this->upload->do_upload('assignment')) {
                    $data['error'] = $this->upload->display_errors();
                    print_r($data);
                } else {
                    $data['upload_data'] = $this->upload->data();
                    $formdata['assignment'] = $data['upload_data']['file_name'];
                }
            }
            
                $result = $this->TeacherModel->updateAssignment($id,$formdata);
                    
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

    function DeleteAssignment($id)
    {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->deleteAssignment($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Assignment Deleted Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Deleting Assignment");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }



// ClassWork

function GetAllClassworks()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllClassworks();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Classwork Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function GetClasswork($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getClasswork($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Classwork Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


function AddClasswork()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->addClasswork($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Classwork Uploaded Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While uploading classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}
function EditClasswork($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->editClasswork($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Classwork Get Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While get classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function UpdateClasswork()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $id = $formdata['id'];
        unset($formdata['id']);
        $result = $this->TeacherModel->updateClasswork($id, $formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Classwork Updated Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Updating classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function DeleteClasswork($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->deleteClasswork($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Classwork Deleted Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Deleting classwork");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

// Homework
function GetAllHomeworks()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllHomeworks();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Homework Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function GetHomework($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getHomework($id);
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Homework Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


function AddHomework()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->addHomework($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Homework Uploaded Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While uploading Homework");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function EditHomework($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->editHomework($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Homework Get Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While get Homework");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function UpdateHomework()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $id = $formdata['id'];
        unset($formdata['id']);
        $result = $this->TeacherModel->updateHomework($id, $formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Homework Updated Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Updating Homework");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function DeleteHomwork($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $result = $this->TeacherModel->deleteHomwork($id);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success",'data' => $result, "message" => "Homework Deleted Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Deleting Homework");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}
// Exam Timetable

function GetExamTimetables()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllExamTimetables();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Exam Timetable Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function AddExamTimeTable()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->AddExamTimetable($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Exam Timetable Added Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Adding Exam Timetable");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


// Timetable

function GetTimetables()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllTimetables();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Exam Timetable Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function AddTimeTable()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->AddTimetable($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Timetable added Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Adding Timetable");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


// Result
function Result()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllResult();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Result Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

function AddResult()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->AddTimetable($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Timetable added Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Adding Timetable");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


// Leaves

function Leaves()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = $this->TeacherModel->getAllLeaves();
        if ($data != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $data, "message" => "Leaves Fetched Successfully");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);

        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


function AddLeave()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formdata = $this->input->post();
        $result = $this->TeacherModel->addLeave($formdata);
        if ($result == true) {
            $this->output->set_status_header(201);
            $response = array("status" => "success", "message" => "Leave added Successfully");
        } else {
            $this->output->set_status_header(500);
            $response = array("status" => "error", "message" => "Some Error Occured While Adding Leave");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

public function GetStudentData()
{

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $classId = $this->input->get('classid');
        $studentId = $this->input->get('studentid');
        
        $result = $this->TeacherModel->getStudentData($classId, $studentId);
        if ($result != null) {
            $this->output->set_status_header(200);
            $response = array("status" => "success", "data" => $result, "message" => "Data Found");
        } else {
            $this->output->set_status_header(404);
            $response = array("status" => "error", "message" => "No Data Found");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Bad Request");
    }
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}

// public function AddAttendance()
// {
//         $raw_post_data = file_get_contents('php://input');
//         $formdata = json_decode($raw_post_data, true);

//         // Debugging to check what data is received

//         $month = date('F');
//         $date = date('Y-m-d');

//         $attendance_data = $formdata['attendance_data'];
//         $response = array("status" => "success", "message" => "Attendance added successfully", "failed_records" => array());

//         foreach ($attendance_data as $record) {
//             $record['month'] = $month;
//             $record['date'] = $date;

//             $result = $this->TeacherModel->addAttendance($record);

//             if (!$result) {
//                 $response['status'] = 'partial_success';
//                 $response['message'] = 'Some records failed to insert';
//                 $response['failed_records'][] = $record;
//             }
//         }

//         if ($response['status'] === 'success') {
//             $this->output->set_status_header(201);
//         } elseif ($response['status'] === 'partial_success') {
//             $this->output->set_status_header(207);
//         } else {
//             $this->output->set_status_header(500);
//         }
//     } else {
//         $this->output->set_status_header(405);
//         $response = array("status" => "error", "message" => "Bad Request");
//     }

//     $this->output->set_content_type("application/json")->set_output(json_encode($response));
// // }
// public function MarkAttendance()
//     {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//         $studentIds = $_POST['studentid'];
//         $classIds = $_POST['classid'];
//         $attendances = $_POST['attendance'];
//         $todayDate = date("d-m-Y");
//         for ($i = 0; $i < count($studentIds); $i++) {
//             $studentId = $studentIds[$i];
//             $classId = $classIds[$i];
//             $attendanceDate = $todayDate;
//             $attendanceMonth = $month;
//             $attendance = "Absent";
//             $array = array(
//                 'studentid' => $studentId,
//                 'classid' => $classId,
//                 'date' => $attendanceDate,
//                 'attendance' => $attendance,
//                 'month' => date('F'),
//             );
//             $this->TeacherModel->addAttendance($array);
//         }
//         for ($i = 0; $i < count($attendances); $i++) {
//             $this->TeacherModel->markPresent($attendances[$i],$todayDate);
//         }
//     }

    public function MarkAttendance()
    {
        // Ensure it's a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        

        $studentIds = $this->input->post('studentid');
        $classIds = $this->input->post('classid');
        $attendances = $this->input->post('attendance');
        $todayDate = date("d-m-Y");

        

        for ($i = 0; $i < count($studentIds); $i++) {
            $studentId = $studentIds[$i];
            $classId = $classIds[$i];
            $attendanceDate = $todayDate;
            $attendance = "Absent"; // Default to Absent

            $array = array(
                'studentid' => $studentId,
                'classid' => $classId,
                'date' => $attendanceDate,
                'attendance' => $attendance,
                'month' => date('F'),
            );
            $this->TeacherModel->addAttendance($array);
        }

        for ($i = 0; $i < count($attendances); $i++) {
            $this->TeacherModel->markPresent($attendances[$i], $todayDate);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // OK
            ->set_output(json_encode(['status' => 'success', 'message' => 'Attendance marked successfully']));
    } else {
        return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405) // Method Not Allowed
                ->set_output(json_encode(['status' => 'error', 'message' => 'Bad Request']));
    }
}


    


public function GetAttendanceReport()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $studentId = $this->input->get('studentid');
        $month = $this->input->get('month');
        if (!empty($studentId) && !empty($month)) {

            $result = $this->TeacherModel->getAttendanceReport($studentId, $month);
            
            if ($result) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "message" => "Attendance Report Retrieved Successfully", "data" => $result);
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No attendance data found for the given criteria");
            }
        } else {
            $this->output->set_status_header(400);
            $response = array("status" => "error", "message" => "Missing student ID or month");
        }
    } else {
        $this->output->set_status_header(405);
        $response = array("status" => "error", "message" => "Invalid Request Method");
    }
    
    $this->output->set_content_type("application/json")->set_output(json_encode($response));
}


}
