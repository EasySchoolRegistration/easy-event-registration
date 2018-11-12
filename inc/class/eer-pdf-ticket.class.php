<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_PDF_Ticket_Generator
{

	public function eer_generate_pdf_callback($ticket_id, $code)
	{
		include_once(EER_PLUGIN_DIR . '/libs/tcpdf/tcpdf.php');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information

		$ticket_data = EER()->ticket->get_ticket_data($ticket_id);
		$event_data = EER()->event->get_event_data($ticket_data->event_id);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle($event_data->title . ' - Ticket ' . $code);

		// set margins
		$pdf->SetMargins(0, 0, 0);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 0);
		$pdf->AddPage();
		$pdf->lastPage();

		$img_file = $ticket_data->pdfticket_design_background;
		$pdf->Image($img_file, 0, 0, 211, 0, 'JPG', '', '', true, 300, '', false, false, 0, false, false, false);

		$name = 'ticket-' . $code . '.pdf';
		list($r, $g, $b) = sscanf($ticket_data->pdfticket_code_color, "#%02x%02x%02x");
		$pdf->SetTextColor($r, $g, $b);
		$pdf->SetFont('helvetica', '', 30, '', 'default', true);
		$pdf->setCellPaddings(102, 10, 0, 0);
		$pdf->MultiCell(200, 50, $ticket_data->title, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

		list($r, $g, $b) = sscanf($ticket_data->pdfticket_code_color, "#%02x%02x%02x");
		$pdf->SetTextColor($r, $g, $b);
		$pdf->SetFont('helvetica', '', 20, '', 'default', true);
		$pdf->setCellPaddings(102, 10, 0, 0);
		$pdf->MultiCell(200, 20, "Ticket number: " . $code, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);

		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('helvetica', '', 10, '', 'default', true);
		$pdf->setCellPaddings(10, 50, 0, 0);
		$pdf->MultiCell(150, 100, $ticket_data->pdfticket_design_description, 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
		$pdf->Output(EER_PLUGIN_DIR . 'temp/' . $name, 'F');

		return EER_PLUGIN_DIR . 'temp/' . $name;
	}


	public static function eer_get_generate_pdf_filter_callback() {
		return 'eer_generate_pdf';
	}
}

add_filter('eer_get_generate_pdf_filter', ['EER_PDF_Ticket_Generator', 'eer_get_generate_pdf_filter_callback']);
add_filter('eer_generate_pdf', ['EER_PDF_Ticket_Generator', 'eer_generate_pdf_callback'], 10, 2);