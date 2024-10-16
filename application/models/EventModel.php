<?php 

class EventModel extends CI_Model{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getAllEvents()
    {
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get("event")->result_array();
        
        if ($data != null) {
            return $data;
        }
        return null;
    }

    function getEventById($id)
    {
        $data=$this->db->get_where("event",array("id"=>$id))->row_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addEvent($formdata)
    {
       return $this->db->insert("event",$formdata); 
    }

    function updateEvent($id,$formdata)
    {
        $this->db->where("id",$id);
        return $this->db->update("event",$formdata);
    }

    function deleteEvent($id)
    {
        $this->db->where("id",$id);
        return $this->db->delete("event");
    }



}