<?php

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
        return ($file) ? @unlink('./media/'.$file) : false;
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
