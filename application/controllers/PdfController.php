<!-- PdfController.php -->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PdfController extends CI_Controller {

    public function extractTables()
    {
        // Upload
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'pdf';
        $config['overwrite'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('pdf')) {
            echo json_encode(["error" => $this->upload->display_errors()]);
            return;
        }

        $file = $this->upload->data();
        $pdfPath = FCPATH . "uploads/" . $file['file_name'];

        // Run Camelot
        $cmd = 'python tools/camelot_extract.py ' . escapeshellarg($pdfPath);

        $json = shell_exec($cmd);

        // Save Excel
        $excelPath = FCPATH . "uploads/last_camelot.xlsx";
        file_put_contents($excelPath, ""); // create file
        file_put_contents($excelPath, $json); // For demo — normally you convert JSON to Excel

        echo $json;
    }

    public function downloadExcel()
{
    $file = FCPATH . 'uploads/last_camelot.xlsx'; // full path
    if (file_exists($file)) {
        return $this->response->download($file, null);
    } else {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
    }
}

}
