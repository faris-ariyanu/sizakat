<?php

use App\Models\Zakat;

if (! function_exists('upload')) {
    function upload($file) {
        if($file){
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)."-".uniqid().".".$file->getClientOriginalExtension();
            $file->move("./media",$filename);
            return $filename;
        }else{
            return false;
        }
    }
}

if (! function_exists('destroy')) {
    function destroy($file) {
        return ($file) ? @unlink('./storage/uploads/'.$file) : false;
    }
}

if (! function_exists('getOriginalName')) {
    function getOriginalName($file) {
        $name   = explode("/",$file);
        return $name[count($name)-1];
    }
}

if (! function_exists('dateformat')) {
    function dateformat($date,$format)
    {
        $date = str_replace('/', '-', $date);
        return date($format, strtotime($date));
    }
}

if (! function_exists('dateformat')) {
    function detediff($date,$date2)
    {
        $diff = strtotime($date2) - strtotime($date);
        return floor($diff / (60 * 60 * 24));
    }
}

if (! function_exists('successResp')) {
    function successResp($message="",$data="")
    {
    	$res = ['status' => true];
        if($message){
        	$res['message'] = $message;
        }
        if($data){
            $res['data'] = $data;
        }

    	return response()->json($res);
    }
}

if (! function_exists('errorResp')) {
    function errorResp($message,$code=200)
    {
    	return response()->json([
                    'status' => false,
                    "message" => $message
                ],$code);
    }
}

if(! function_exists('getAge')){
    function getAge($date){
        $diff = date_diff(date_create($date), date_create(date("Y-m-d")));
        return $diff->format('%y');
    }
}

if(! function_exists('getPaymentMethod')){
    function getPaymentMethod(){
        return[
            [
                "id" => "1",
                "name" => "Transfer Bank",
            ],
            [
                "id" => "2",
                "name" => "QR Code",
            ],
            [
                "id" => "3",
                "name" => "Beras",
            ],
            [
                "id" => "4",
                "name" => "Cash",
            ]
        ];
    }
}

if(! function_exists('getPaymentMethodById')){
    function getPaymentMethodById($id){
        foreach(getPaymentMethod() as $r){
            if($r['id'] == $id){
                return $r['name'];
                break;
            }    
        }
    }
}

if (! function_exists('number')) {
    function number($number,$currency = false)
    {
        return ($currency ? "Rp " : "") .number_format($number,0,",",".");
    }
}

if(! function_exists('getTypeZakat')){
    function getTypeZakat(){
        return[
            [
                "code" => "FDY",
                "name" => "Fidyah",
            ],
            [
                "code" => "FTR",
                "name" => "Zakat Fitrah",
            ],
            [
                "code" => "MAL",
                "name" => "Zakat Mal",
            ],
            [
                "code" => "SDQ",
                "name" => "Infaq Sedekah",
            ]
        ];
    }
}

if(! function_exists('getTypeZakatByCode')){
    function getTypeZakatByCode($code){
        foreach(getTypeZakat() as $r){
            if($r['code'] == $code){
                return $r['name'];
                break;
            }    
        }
    }
}

if(! function_exists('getStrukturKeluarga')){
    function getStrukturKeluarga(){
        return[
            "Bapak","Ibu","Anak","Adik","Kakak","Saudara","Asisten Rumah Tangga"
        ];
    }
}

if (! function_exists('SumDateWithHour')) {
    function SumDateWithHour($date,$hour)
    {
        return date("Y-m-d H:i:s", strtotime('+'.$hour.' hours',strtotime($date)));
    }
}

if (!function_exists('getUniqueCodeTrx')) {
    function getUniqueCodeTrx(){
        $order      = Zakat::where("status","!=",0)->orderBy("unique_code","desc");
        $lastcode   = ($order->count()) ? (int)$order->first()->unique_code : "0";
        $lastcode   = ($lastcode == 100) ? 1 : $lastcode + 1;
        return str_pad($lastcode, 3, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('daynow')) {
    function daynow(){
        $hari = date ("D");
    
        switch($hari){
            case 'Sun':
                $hari_ini = "Ahad";
            break;
    
            case 'Mon':			
                $hari_ini = "Senin";
            break;
    
            case 'Tue':
                $hari_ini = "Selasa";
            break;
    
            case 'Wed':
                $hari_ini = "Rabu";
            break;
    
            case 'Thu':
                $hari_ini = "Kamis";
            break;
    
            case 'Fri':
                $hari_ini = "Jumat";
            break;
    
            case 'Sat':
                $hari_ini = "Sabtu";
            break;
            
            default:
                $hari_ini = "Tidak di ketahui";		
            break;
        }
    
        return $hari_ini;
    
    }
}

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal)
    {
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }
}