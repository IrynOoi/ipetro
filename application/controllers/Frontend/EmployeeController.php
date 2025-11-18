<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeController extends CI_Controller {

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->model('EmployeeModel');
        //$data['employee']=$this->EmployeeModel->getEmployee();
        //another way to pass data from db to view
       $employee=$this->EmployeeModel->getEmployee();
        $this->load->view('Frontend/employee', ['employee'=>$employee]);
         $this->load->view('templates/footer');
    }

    public function create()
    {
        
        $this->load->view('templates/header');
        $this->load->view('Frontend/create');
         $this->load->view('templates/footer');
    }

    public function store()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
 

        if ($this->form_validation->run()) {
            $data=['first_name'=>$this->input->post('first_name'),
             'last_name'=>$this->input->post('last_name'),
             'email'=>$this->input->post('email'),
             'phone_number'=>$this->input->post('phone_number'),
            ] ;
            $this->load->model('EmployeeModel','emp');
            $this->emp->insertEmployee($data);
            $this->session->set_flashdata('status', 'Employee Data inserted successfully');
            redirect(base_url('employee'));
        }
            else {
           $this->create();
        //    redirect(base_url('employee/add'));
            }
    }
    public function edit($id)
    {
        $this->load->view('templates/header');
        $this->load->model('EmployeeModel');
        $data['employee'] =  $this->EmployeeModel->editEmployee($id);
   
        $this->load->view('Frontend/edit', $data);
        $this->load->view('templates/footer');
    }
    
    public function update($id)
    {
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        
        if ($this->form_validation->run()) :
        $data=['first_name'=>$this->input->post('first_name'),
                'last_name'=>$this->input->post('last_name'),
                'email'=>$this->input->post('email'),
                'phone_number'=>$this->input->post('phone_number'),
                ] ;
        $this->load->model('EmployeeModel');
        $this->EmployeeModel->updateEmployee($data, $id);
                redirect(base_url('employee'));

        else :
            $this->edit($id);
        
    endif;

 
    }

    public function delete($id)
    {
        $this->load->model('EmployeeModel');
        $this->EmployeeModel->deleteEmployee($id);
        redirect(base_url('employee'));
    }
}

?>