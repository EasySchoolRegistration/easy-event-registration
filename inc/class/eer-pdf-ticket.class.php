<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_PDF_Ticket {

	public function generate_pdf($ticket_id, $code) {
		include_once(EER_PLUGIN_DIR . '/libs/tcpdf/tcpdf.php' );

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
		$pdf->SetFont('helvetica', '', 20 , '', 'default', true );
		$pdf->Text(157, 52, $code, false, false, true, 0, 0, '', false, '', 0, false, 'T', 'M', false );


		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('helvetica', '', 14 , '', 'default', true );
		$pdf->Text(10, 100, $ticket_data->pdfticket_design_description, false, false, true, 0, 0, '', false, '', 0, false, 'T', 'M', false );
		$pdf->Output(EER_PLUGIN_DIR . 'temp/' . $name, 'F');

		return EER_PLUGIN_DIR . 'temp/' . $name;
	}

}