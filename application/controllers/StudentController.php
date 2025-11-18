<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentController extends CI_Controller {

    public function index() {
        $this->load->model('StudentModal','stud');
        // $student=$this->StudentModal->student_data();
        // $student = new StudentModal();
        // $student = $student->student_data();
        $student=$this->stud->student_data();
        // $student_class=$this->stud->student_class();
        echo "Student Name: ". $student ;
    }

    public function show($id) {
        // echo $id ;
        $this->load->model('StudentModal','stud');
        $select_stud=$this->stud->student_show($id);
        echo $select_stud ;
        //student_show($id)
    }
}
?>