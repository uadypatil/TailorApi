<?php  

defined("BASEPATH") or exit("No direct Script Access Allowed");

class TeacherModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();   
        $this->load->database();
    }

    function getSyllabus()
    {
        $data=$this->db->get("syllabus")->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }
    function checkSyllabus($formdata)
    {
        $this->db->where('classid', $formdata['classid']);
        $data=$this->db->get("syllabus")->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }
    function uploadSyllabus($formdata)
    {
        return $this->db->insert("syllabus",$formdata);
    }

    function updateUploadedSyllabus($id,$formdata)
    {
        $this->db->where("id",$id);
        return $this->db->update("syllabus",$formdata);
    }

    function editSyllabus($id){
        $this->db->where('id', $id);
        $data=$this->db->get("syllabus")->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function updateSyllabus($id, $formdata){
        $this->db->where('id', $id);
        $data=$this->db->update("syllabus", $formdata);
        if($data!=null)
        {
            return $data;
        }
        return null;
    }



    function getLearningMaterials()
    {
        $data=$this->db->get("learning_material")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function getLearningMaterial($id)
    {
        $data=$this->db->get_where("learning_material",array("id"=>$id))->row_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

   

    function uploadLearningMaterial($formdata)
    {
        return $this->db->insert("learning_material",$formdata);
    }

    function updateLearningMaterial($id,$formdata)
    {
        $this->db->where("id",$id);
        return $this->db->update("learning_material",$formdata);  
    }

    function deleteLearningMaterial()
    {

    }


    // Assignments
    function getAllAssignments()
    {
        $data=$this->db->get("assignment")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function getAssignment($id)
    {
        $data=$this->db->get_where("assignment",array("id"=>$id))->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addAssignment($formdata)
    {
        return $this->db->insert("assignment",$formdata);
    }

    function updateAssignment($id,$formData)
    {
        $this->db->where("id",$id);
        $result=$this->db->update("assignment",$formData);
        return $result;
    }

    function deleteAssignment($id){
        $this->db->where('id', $id);
        $data=$this->db->delete("assignment");
        if($data)
        {
            return true;
        }
        
    }




    // Classwork
    function getAllClassworks()
    {
        $data=$this->db->get("classwork")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function getClasswork($id)
    {
        $data=$this->db->get_where("classwork",array("id"=>$id))->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addClasswork($formdata)
    {
        return $this->db->insert("classwork",$formdata);
    }

    function editClasswork($id){
        $this->db->where('id', $id);
        $data=$this->db->get("classwork")->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function updateClasswork($id, $formdata){
        $this->db->where('id', $id);
        $data=$this->db->update("classwork", $formdata);
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function deleteClasswork($id){
        $this->db->where('id', $id);
        $data=$this->db->delete("classwork");
        if($data)
        {
            return true;
        }
        
    }

    // Homework
    function getAllHomeworks()
    {
        $data=$this->db->get("homework")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function getHomework($id)
    {
        $data=$this->db->get_where("homework",array("id"=>$id))->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addHomework($formdata)
    {
        return $this->db->insert("homework",$formdata);
    }


    function editHomework($id){
        $this->db->where('id', $id);
        $data=$this->db->get("homework")->row();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function updateHomework($id, $formdata){
        $this->db->where('id', $id);
        $data=$this->db->update("homework", $formdata);
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function deleteHomework($id){
        $this->db->where('id', $id);
        $data=$this->db->delete("homework");
        if($data)
        {
            return true;
        }
        
    }

    // Exam Timetables


 function getAllExamTimetables()
    {
        $data=$this->db->get_where("exam_timetable")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function AddExamTimetable($formdata)
    {
        return $this->db->insert("exam_timetable",$formdata);
    }

    // Time Tables
   
    function getAllTimetables()
    {
        $data=$this->db->get_where("timetable")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function AddTimetable($formdata)
    {
        return $this->db->insert("timetable",$formdata);
    }


    //Result
    function getAllResult()
    {
        $data=$this->db->get("result")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addResult($formdata)
    {
        return $this->db->insert("result",$formdata);
    }




    // Leaves
    function getAllLeaves()
    {
        $data=$this->db->get("leaves")->result_array();
        if($data!=null)
        {
            return $data;
        }
        return null;
    }

    function addLeave($formdata)
    {
        return $this->db->insert("leaves",$formdata);

    }

    public function getStudentData($classId, $studentId)
    {

        if (!empty($classId)) {
            $this->db->where('classid', $classId);
        }
        
        // Check if studentId is not empty
        if (!empty($studentId)) {
            $this->db->where('studentid', $studentId);
        }
        if(!empty($classId) || !empty($studentId)){
        $query = $this->db->get('student');
        }
        return $query->result();
    }

    function addAttendance($formdata)
    {
        return $this->db->insert("attendance",$formdata);

    }
    function markPresent($studentid, $date)
    {
        $this->db->where('studentid', $studentid);
        $this->db->where('date', $date);
        $this->db->update('attendance', array('attendance' => "Present"));
    }
    function getAttendanceReport($studentId, $month)
    {
        // print_r($formdata);die;
        $this->db->where('studentid',$studentId);
        $this->db->where('month',$month);
        $data=$this->db->get("attendance")->result();

        if($data!=null)
        {
            return $data;
        }
        return null;

    }
    
}



?>