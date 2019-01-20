<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dompdf_helper
 *
 * @author Parimal
 */
function pdf_create($html, $filename = '', $stream = TRUE, $set_paper = '', $attach = null, $folder_name = null)
{
		$_this = get_instance();
		
		$_this->load->library('Pdf');
 		$_this->load->helper('language');
		$pdf =  new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'rtl';
		$lg['a_meta_language'] = 'fa';
		$lg['w_page'] = 'page';
		
		$pdf->setLanguageArray($lg);
		
		$pdf->SetFont('aealarabiya', '', 12);
		
		$pdf->AddPage();
		
		$lng = $_this->admin_model->get_lang();
		if (!empty($lng) && $lng == 'arabic') {
			$pdf->setRTL(true);
		}else{
			$pdf->setRTL(false);
		}
		
		$pdf->SetFontSize(10);
		
		$pdf->WriteHTML($html, true, 0, true, 0);
		
		$pdf->Output("" . $filename . ".pdf", 'I');

/*   require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);

    if ($set_paper != '') {
        $dompdf->set_paper(array(0, 0, 900, 841), 'portrait');
    } else {
        $dompdf->set_paper("a4", "landscape");
    }
    $dompdf->render();
    if ($stream) {
        $pdf_string = $dompdf->output();
        if (!empty($attach)) {
            if (!empty($folder_name)) {
                $folder = "uploads/" . $folder_name . '/' . $filename . ".pdf";;
            } else {
                $folder = "uploads/" . $filename . ".pdf";;
            }
            file_put_contents($folder, $pdf_string);
        } else {
            $dompdf->stream($filename . ".pdf");
        }
    } else {
        return $dompdf->output();
    }
*/}
