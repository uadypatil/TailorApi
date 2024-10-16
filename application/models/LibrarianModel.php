<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class LibrarianModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }

    // function to get librarians
    function getLibrarians()
    {
        $data = $this->db->get("libariandata")->result();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to add librarian
    function addLibrarian($formData)
    {
        $result = $this->db->insert("libariandata", $formData);
        return $result;
    }   // function ends

    // function to get librarian by librarian id    
    function getLibrarianById($id)
    {
        $data = $this->db->get_where("libariandata", array("id" => $id))->row();
        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }   // function ends

    // function to update librarian data
    function updateLibrarian($id, $formData)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("libariandata", $formData);
        return $result;
    }   // function ends

    // function to delete librarian
    function deleteLibrarian($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->delete("libariandata");
        return $result;
    }   // function ends
}
 