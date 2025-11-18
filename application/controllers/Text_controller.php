<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Text_controller extends CI_Controller {

    public function index()
    {
        $this->load->view('text'); // loads your HTML SheetJS page
    }
}
