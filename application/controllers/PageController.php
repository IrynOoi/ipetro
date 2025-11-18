<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PageController extends CI_Controller {

    public function index() {
        echo "I am index method -Page controller - HOME in url";
    }

    public function aboutus() {
        echo "I am about page.";
    } 

    public function blog($blog_url = '') 
    {
      echo"$blog_url";
      $this->load->view('blogview');
    }

    public function demopage() 
    {
      $this->load->model('StudentModal');
      $title=$this->StudentModal->demo(); 
        // $data['title'] = "hello,I am OOI XIEN XIEN";
      $data['title'] = $title;
       $data['body'] = "welcome to my channel";
      $this->load->view('demopage',$data);
      //passing data to view
    }


}