<?php 

class EventManager extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model("EventModel");
    }

    function GetAllEvents()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $events = $this->EventModel->getAllEvents();
            if ($events!=null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $events, "message" => "Events Fetched Successfully");
            } else {
                $this->output->set_status_header(404);

                $response = array("status" => "error", "message" => "No Events Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function GetEvent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $event = $this->EventModel->getEventById($id);
            if ($event != null) {
                $this->output->set_status_header(200);
                $response = array("status" => "success", "data" => $event, "message" => "Event Fetched Successfully");
            } else {
                $this->output->set_status_header(404);
                $response = array("status" => "error", "message" => "No Event Found");
            }
        } else {
            $this->output->set_status_header(405);

            $response = array("status" => "error", "message" => "Bad Request");
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function AddEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $formdata = $this->input->post();
                    $result = $this->EventModel->addEvent($formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200 );
                        $response = array("status" => "success", "message" => "Event Added Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Adding Event");
                    }
                
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function UpdateEvent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = $this->EventModel->getEventById($id);
            if ($event != null) {
                    $formdata = $this->input->post();
                    $result = $this->EventModel->updateEvent($id,$formdata);
                    if ($result ==true) {
                        $this->output->set_status_header(200 );
                        $response = array("status" => "success", "message" => "Event Data Updated Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Updating event Data");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Event Found");
                } 
            }
            else {
                $this->output->set_status_header(405);
                $response = array("status" => "error", "message" => "Bad Request");
            }
            $this->output->set_content_type("application/json")->set_output(json_encode($response));
    }

    function DeleteEvent($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $event = $this->EventModel->getEventById($id);
            if ($event != null) {
                    $result = $this->EventModel->deleteEvent($id);
                    if ($result ==true) {
                        $this->output->set_status_header(200);
                        $response = array("status" => "success", "message" => "Event Deleted Successfully");
                    } else {
                        $this->output->set_status_header(500);
                        $response = array("status" => "error", "message" => "Some Error Occured While Deleting Event");
                    }
                }
                else{
                    $this->output->set_status_header(404);
                    $response = array("status" => "error", "message" => "No Event Found");
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