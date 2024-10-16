<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class StaffModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function getStaffs()
    {
        $Staffs=$this->db->get("staff")->result();
        if($Staffs!=null)
        {
            
            foreach ($Staffs as $staff) {
                $staff->password=$this->MainModel->decryptData($staff->password);
                }
            return $Staffs;
        }
        else{
            return null;
        }
    }

    function registerStaff($formData)
    {
        $result=$this->db->insert("staff",$formData);
        return $result;
    }

    function getStaff($id)
    {
        $Staff=$this->db->get_where("staff",array("id"=>$id))->row();
        if($Staff!=null)
        {
            $Staff->password=$this->MainModel->decryptData($Staff->password);
            return $Staff;
        }
        else{
            return null;
        }
    }

    function updateStaff($id,$formData)
    {
        $this->db->where("id",$id);
        $result=$this->db->update("staff",$formData);
        return $result;
    }

    function deleteStaff($id)
    {
        $this->db->where("id",$id);
        $result=$this->db->delete("staff");
        return $result;
    }

    function updatePassword($id,$password)
    {
        $this->db->set("password",$password);
        $this->db->where("id",$id);
        $result = $this->db->update("staff");
        return $result;
    }
}