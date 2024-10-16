<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class ClassModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("MainModel");

    }
    function getClasses()
    {
        $Class=$this->db->get("class")->result();
        if($Class!=null)
        {
            return $Class;
        }
        else{
            return null;
        }
    }

    function registerClass($formData)
    {
        $result=$this->db->insert("class",$formData);
        return $result;
    }

    function getClass($id)
    {
        $Class=$this->db->get_where("class",array("id"=>$id))->row();
        if($Class!=null)
        {
            return $Class;
        }
        else{
            return null;
        }
    }

    function updateClass($id,$formData)
    {
        $this->db->where("id",$id);
        $result=$this->db->update("class",$formData);
        return $result;
    }

    function deleteClass($id)
    {
        $this->db->where("id",$id);
        $result=$this->db->delete("class");
        return $result;
    }

    public function get_Last_Value_in_class_id()
    {
        $this->db->select('id');
        $this->db->from('class');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $lastValue = $row->id;
            $newvalue = $lastValue + 1;
            $newStdiD = "CLS-" . $newvalue;
            return $newStdiD;
        } else {
            return null;
        }
    }
}



?>