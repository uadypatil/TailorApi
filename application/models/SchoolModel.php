<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class SchoolModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");
    }
    function getSchools()
    {
        $Schools = $this->db->get("school")->result();
    if ($Schools != null) {
        foreach ($Schools as $school) {         
            $school->password=$this->MainModel->decryptData($school->password);
        }
        return $Schools;
    } else {
        return null;
    }
    }

    function registerSchool($formData)
    {
        $result=$this->db->insert("school",$formData);
        return $result;
    }

    function getSchool($id)
    {
        $school=$this->db->get_where("school",array("id"=>$id))->row();
        if($school!=null)
        {
            $school->password=$this->MainModel->decryptData($school->password);
            return $school;
        }
        else{
            return null;
        }
    }

    function updateSchool($id,$formData)
    {
        $this->db->where("id",$id);
        $result=$this->db->update("school",$formData);
        return $result;
    }

    function deleteSchool($id)
    {
        $this->db->where("id",$id);
        $result=$this->db->delete("school");
        return $result;
    }

    function updatePassword($id,$password)
    {
        $this->db->set("password",$password);
        $this->db->where("id",$id);
        $result = $this->db->update("school");
        return $result;
    }

}