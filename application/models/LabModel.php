<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class LabModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }

    // function to get labs
    function getLabs()
    {
        $data = $this->db->get("labdata")->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to add lab
    function addLab($formData)
    {
        $result = $this->db->insert("labdata", $formData);
        return $result;
    }   // function ends

    // function to get lab by lab id    
    function getLabById($id)
    {
        $data = $this->db->get_where("labdata", array("id" => $id))->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to update lab data
    function updateLab($id, $formData)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("labdata", $formData);
        return $result;
    }   // function ends

    // function to delete lab
    function deleteLab($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->delete("labdata");
        return $result;
    }   // function ends

}
 