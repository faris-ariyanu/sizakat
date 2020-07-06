<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zakat;
use App\Models\ZakatItem;
use App\Models\Periode;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data['title']          = "Home";
        return view('pages.home',$data);
    }

    public function reportZakat(Request $request)
    {

        $data['periode']    = Periode::where("status",1)->orderBy("year","desc")->first();
        $data['title']      = "Laporan Penerimaan Zakat";
        return view('pages.report-zakat',$data);
    }

    public function checkZakat(Request $request)
    {
        $data['title']          = "Home";
        return view('pages.check-zakat',$data);
    }

    public function doCheckZakat(Request $request)
    {
        $data['transaction_number'] = $request->transaction_number;
        $data['zakat']              = Zakat::where("transaction_number",$request->transaction_number)->first();
        return view('pages.result-check-zakat',$data);
    }
}
