<?php

defined("BASEPATH") or exit("No direct Script Access Allowed");

class StudentModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function getStudents()
    {
        $Students = $this->db->get("student")->result();
        if ($Students != null) {
            foreach ($Students as $Student) {
                $Student->password = $this->MainModel->decryptData($Student->password);
            }
            return $Students;
        } else {
            return null;
        }
    }

    function addStudent($formData)
    {
        $result = $this->db->insert("student", $formData);
        return $result;
    }

    function getStudent($id)
    {
        $Student = $this->db->get_where("student", array("id" => $id))->row();
        if ($Student != null) {
            $Student->password = $this->MainModel->decryptData($Student->password);
            return $Student;
        } else {
            return null;
        }
    }


    function updateStudent($id, $formData)
    {
        $this->db->where("id", $id);
        $result = $this->db->update("student", $formData);
        return $result;
    }

    function deleteStudent($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->delete("student");
        return $result;
    }

    function updatePassword($id, $password)
    {
        $this->db->set("password", $password);
        $this->db->where("id", $id);
        $result = $this->db->update("student");
        return $result;
    }
    function getStudentId($studentid)
    {
        $Student = $this->db->get_where("student", array("studentid" => $studentid))->row();
        if ($Student != null) {
            $Student->password = $this->MainModel->decryptData($Student->password);
            return $Student;
        } else {
            return null;
        }
    }

    function getStudentContact($contact)
    {
        $Student = $this->db->get_where("student", array("contact" => $contact))->row();
        if ($Student != null) {
            $Student->password = $this->MainModel->decryptData($Student->password);
            return $Student;
        } else {
            return null;
        }
    }

    function getStudentEmail($email)
    {
        $Student = $this->db->get_where("student", array("email" => $email))->row();
        if ($Student != null) {
            $Student->password = $this->MainModel->decryptData($Student->password);
            return $Student;
        } else {
            return null;
        }
    }

}
