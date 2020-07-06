<?php

define(base_path().'/app/Library/fpdf/FPDF_FONTPATH','font/');
include base_path()."/app/Library/fpdf/fpdf.php";

class Invoice extends FPDF
{
	public $profile;
	public $finish;
	public $qurban;
	public $yStart;
	public $total;

	function SetWidths($w)
  	{
      $this->widths=$w;
  	}

  	function SetAligns($a)
  	{
      $this->aligns=$a;
  	}

  	function Row($data,$align) // fungsi untuk membuat tabel pada pdf
	{
		$nb=0;
		for($i=0;$i<count((array)$data);$i++){
			$nb=max($nb,$this->NbLines($this->widths[$i],strtoupper($data[$i])));
		}
		$h = $nb*5;

	    $this->total = $this->total-1;
		if($this->total == 0){
			$this->CheckPageBreak(30);
		}else{
			$this->CheckPageBreak(5);
		}

	    //Draw the cells of the Row
	    for($i=0;$i<count((array)$data);$i++)
	    {
	        $w = $this->widths[$i];
	        $a = 'L';
	        if($align[$i] && isset($align[$i]))
	        {
	        	$a = $align[$i];
	        }
	        //Save the current position
	        $x=$this->GetX();
	        $y=$this->GetY();
	        //Print the text
	        $this->MultiCell($w,5,$data[$i],0,$a);
	        //Put the position to the right of the cell
	        $this->SetXY($x+$w,$y);
	        
	    }
	    //Go to the next line
	    $this->Ln($h);
	}

	function CheckPageBreak($h)
	{
	    if($this->GetY()+$h>$this->PageBreakTrigger){
	        $this->AddPage($this->CurOrientation);
			$this->SetXY(10,$this->yStart);
		}
	}

	function NbLines($w,$txt)
	{
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}

	function SetCharSpacing($cs) { 
		$this->_out(sprintf('BT %.3F Tc ET',$cs*$this->k)); 
	} 

	function header(){

		//========= Title Report =========== //
		if(file_exists(base_path('public/images/logo.png')))
		{
			$this->image(base_path('public/images/logo.png'),10,10,15,15);
		}

		$this->SetCharSpacing(0.2);
		$this->SetFont('arial','B',10);
		$this->SetXY(30,10);
		$this->MultiCell(100,5,"UPZIS - Masjid Jami' Baiturrahmah",0,'L');
		$this->SetX(30);
		$this->SetFont('arial','',9);
		$this->MultiCell(100,5,"Jl. Agraria No.1 - Komp. DDN I, Pondok Labu, 12450",0,'L');
		$this->SetX(30);
		$this->MultiCell(100,5,"Website : http://mbr.or.id ; Email : info@mbr.or.id",0,'L');
		$y = $this->GetY();

		$this->line(11,$y+2,200,$y+2);

		if($this->PageNo() == 1){
			$yb		= $y + 6;
			$this->SetY($yb);
			$this->SetX(10);
			$this->SetFont('arial','B',10);
			$this->MultiCell(190,5,"Tanda Terima Hewan Qurban",0,'C');
			$this->SetFont('arial','',9);
			$ytop = $this->GetY() + 5;

			$ynext = $this->GetY();
            $this->SetY($ynext);
			$this->SetFont('arial','',9);
			$this->SetX(10);
			$this->MultiCell(40,5,"Tgl Transaksi ",0,'L');
			$this->SetXY(50,$ynext);
			$this->MultiCell(3,5,":",0,'L');
			$this->SetXY(53,$ynext);
            $this->MultiCell(40,5,$this->qurban->created_at,0,'L');
            
            $ynext = $this->GetY();
            $this->SetY($ynext);
			$this->SetFont('arial','',9);
			$this->SetX(10);
			$this->MultiCell(40,5,"Nama Pembayar",0,'L');
			$this->SetXY(50,$ynext);
			$this->MultiCell(3,5,":",0,'L');
			$this->SetXY(53,$ynext);
            $this->MultiCell(40,5,$this->qurban->name,0,'L');

            $ynext = $this->GetY();
            $this->SetY($ynext);
			$this->SetFont('arial','',9);
			$this->SetX(10);
			$this->MultiCell(40,5,"Alamat Pembayar",0,'L');
			$this->SetXY(50,$ynext);
			$this->MultiCell(3,5,":",0,'L');
			$this->SetXY(53,$ynext);
            $this->MultiCell(40,5,$this->qurban->address,0,'L');

            $ynext = $this->GetY();
            $this->SetY($ynext);
			$this->SetFont('arial','',9);
			$this->SetX(10);
			$this->MultiCell(40,5,"Telepon Pembayar",0,'L');
			$this->SetXY(50,$ynext);
			$this->MultiCell(3,5,":",0,'L');
			$this->SetXY(53,$ynext);
            $this->MultiCell(40,5,$this->qurban->phone,0,'L');

            $ynext = $this->GetY();
            $this->SetY($ynext);
			$this->SetFont('arial','',9);
			$this->SetX(10);
			$this->MultiCell(40,5,"Hewan Qurban",0,'L');
			$this->SetXY(50,$ynext);
			$this->MultiCell(3,5,":",0,'L');
			$this->SetXY(53,$ynext);
            $this->MultiCell(40,5,$this->qurban->hewanQurban->hewanQurban->name. ' - '.$this->qurban->hewanQurban->no_urut,0,'L');

		}

		// ======== Table header ========== //
		$yHeader = ($this->PageNo() == 1) ? $ynext + 20 : 20;
		$this->SetXY(10,$yHeader);
		$this->SetFont('arial','',9);
		$this->Cell(10,5,'No',0,0,'L',0);
		$this->Cell(35,5,'Nama',0,0,'L',0);
		$this->Cell(35,5,'1/3 Bagian Diambil',0,0,'L',0);
		$this->Cell(30,5,'Biaya Potong',0,1,'L',0);
		$this->line(11,$this->getY(),200,$this->getY());
		$this->yStart = $yHeader+6;
	}

	function footer(){
        $this->SetXY(11,250);
		$this->SetFont('arial','B',10);
        $this->MultiCell(100,5,"PELAYANAN MBR :",0,'L');

        $this->SetFont('arial','',9);
        $yCurrent = $this->GetY();
        $this->SetXY(11,$yCurrent);
        $this->MultiCell(60,5,"1. Muallaf Center",0,'L');
        $this->SetXY(73,$yCurrent);
        $this->MultiCell(60,5,"5. Free Nasi Bungkus Setiap Jumat",0,'L');
        $this->SetXY(135,$yCurrent);
		$this->MultiCell(60,5,"9. Pengurusan Jenazah",0,'L');

        $yCurrent = $this->GetY();
        $this->SetXY(11,$yCurrent);
        $this->MultiCell(60,5,"2. Kajian Alquran & Hadist",0,'L');
        $this->SetXY(73,$yCurrent);
        $this->MultiCell(60,5,"6. Muslim Library",0,'L');
        $this->SetXY(135,$yCurrent);
		$this->MultiCell(60,5,"10. Free Wifi",0,'L');


        $yCurrent = $this->GetY();
        $this->SetXY(11,$yCurrent);
        $this->MultiCell(60,5,"3. Kultum Setiap Ba'da Subuh",0,'L');
        $this->SetXY(73,$yCurrent);
        $this->MultiCell(60,5,"7. Pengajian Jamaah Ibu-Ibu",0,'L');
        $this->SetXY(135,$yCurrent);
        $this->MultiCell(60,5,"11. Free Charging",0,'L');
        
        $yCurrent = $this->GetY();
        $this->SetXY(11,$yCurrent);
        $this->MultiCell(60,5,"4. Ta'jil Buka Puasa Senin - Kamis",0,'L');
        $this->SetXY(73,$yCurrent);
        $this->MultiCell(60,5,"8. Fasilitas Ambulance Gratis",0,'L');
        $this->SetXY(135,$yCurrent);
        $this->MultiCell(60,5,"12. Buka 24 Jam",0,'L');


		$this->SetFont('arial','',9);
		$this->line(11,280,200,280);
		$this->SetXY(10,-15);
        $this->MultiCell(100,5,"Dicetak Pada : ".date('d M Y')." / Pukul ".date('H:i')." WIB",0,'L');
        $this->MultiCell(100,5,"Sistem Informasi Zakat - MBR @ ".date('Y'),0,'L');
		$this->SetXY(-40,-10);
        $this->MultiCell(30,5,"Page ".$this->PageNo()."/{nb}",0,'R');
	}

}

try{

    $pdf  			= new Invoice('P','mm','A4');
    $pdf->qurban 	= $qurban;
    $pdf->total 	= 1;
    $pdf->SetTitle("Kwitansi ".$qurban->id);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // ======== End Table header ========== //
    $pdf->SetXY(10,$pdf->yStart);
    $pdf->SetFont('arial','',9);
    $pdf->SetWidths([10,35,35,30]);
    $align 	= ['L','L','L','L'];

    $i = 1;
    foreach($items as $row){
    $pdf->Row([$i,
                $row->name,
                $row->status_cacahan ? 'Diambil' : "Tidak Diambil",
                number($qurban->income_value,1),
                ],$align);
                $i++;
    }

    $pdf->Ln(1);
    $pdf->line(11,$pdf->getY(),200,$pdf->getY());
    $pdf->SetFont('arial','',9);
    $pdf->Ln(2);

    $pdf->SetFont('arial','',9);
    $y = $pdf->GetY();
    $pdf->SetXY(120,$y);
    $pdf->MultiCell(30,5,"Total Nominal:",0,'L');
    $pdf->SetXY(150,$y);
    $pdf->MultiCell(50,5,number($qurban->income_value,1),0,'R');

    $pdf->Ln(10);
    $pdf->SetFont('arial','',10);
    $y = $pdf->GetY();
    $pdf->SetXY(140,$y);
    $pdf->MultiCell(30,5,"Operator",0,'C');

    $pdf->Ln(15);
    $pdf->SetFont('arial','',10);
    $y = $pdf->GetY();
    $pdf->SetXY(105,$y);
    $pdf->MultiCell(100,5,$user,0,'C');

    $pdf->Ln(5);
    $pdf->finish = true;

    $pdf->Output("Kwitansi-#".$qurban->id.".pdf",'I');
    exit;
} catch(\Exception $e) {
    dd($e);
}

?>
