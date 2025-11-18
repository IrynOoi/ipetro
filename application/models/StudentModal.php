<?php

class StudentModal extends CI_Model {

    public function student_data() {

      $stud_class=$this->student_class();
      return $stud_name = "Rohit . His class is:". $stud_class;
    }

    private function student_class() 
    {
      return $stud_class = "BCA";
    }

    public function student_show($id) 
    {
        if ($id=='1') {
            return $result="User 1";
        } elseif ($id=='2') {
            return $result="User 2";
        }

    }

public function demo()
{
   return $title="hello,I am OOI XIEN XIEN.i am coming from student  model";
}


}

?>
