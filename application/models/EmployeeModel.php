<?php
class EmployeeModel extends CI_Model {


    public function getEmployee() {
        $query = $this->db->get('employee');
        return $query->result();
      
    }
    public function insertEmployee($data) {
        return $this->db->insert('employee', $data);
        
    }
    public function editEmployee($id) {
        $query = $this->db->get_where('employee', ['id' => $id]);
        //another way to edit data
        return $query->row();
    }

    public function updateEmployee($data,$id) {

       return $this->db->update('employee', $data, array('id' => $id));
    }

    public function deleteEmployee($id) {
        return $this->db->delete('employee', array('id' => $id));
        // $this->db->delete('mytable', array('id' => $id));
    }

}