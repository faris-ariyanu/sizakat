@extends('layouts.index')

@section('content')

    <!--Donate Section-->
    <section class="donate-section" style="margin-top:15px;">
    	<div class="auto-container">
            
            <div class="donate-form-section">
            	
                <div class="donation-form-outer col-md-8">
            	<form method="post">
                	
                    <!--Form Portlet-->
                    <div class="form-portlet">
                        <h3>Transaksi Zakat #{{$transaction_number}}</h3>
                        @if($zakat)
                        <div class="row clearfix">
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Periode</b>
                                    <span class="pull-right">{{$zakat->periode}}</span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Tanggal</b>
                                    <span class="pull-right">{{$zakat->date}}</span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Jatuh Tempo</b>
                                    <span class="pull-right">{{$zakat->expire_time}}</span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Mode Pembayaran</b>
                                    <span class="pull-right">{{$zakat->payment_method_name}}</span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Status</b>
                                    <span class="pull-right">{{$zakat->status['name']}}</span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Pembayar</b>
                                    <span class="pull-right">
                                        {{$zakat->name}} - {{$zakat->address}}
                                    
                                    </span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <h4>Rincian Zakat</h4>
                            </div>
                            @foreach($zakat->items as $row)
                                <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                    <p><b>{{$row->muzakki_name}} - {{$row->muzakki_relation}}</b></p>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tipe Zakat</th>
                                                <th>Tipe Transaksi</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($row->zakat as $res)
                                                <tr>
                                                    <td>{{$res->zakat_type}}</td>
                                                    <td>{{$res->transaction_type}}</td>
                                                    <td>
                                                        @if($res->transaction_type == "Beras")
                                                            {{number($res->income_goods)}} Kg
                                                        @else
                                                            {{number($res->income_value,1)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Total Uang</b>
                                    <span class="pull-right">
                                        {{number($zakat->total_money,1)}}
                                    </span>
                                </p>
                                <hr class="sm-hr">
                            </div>
                            <div class="form-group sm-hr col-lg-12 col-md-12 col-xs-12">
                                <p><b>Total Beras</b>
                                    <span class="pull-right">
                                        {{number($zakat->total_goods)}} Kg
                                    </span>
                                </p>
                            </div>
                            
                        </div>
                        @else
                            <div class="row">
                                <h5 class="col-md-12 alert alert-info">Transaksi tidak ditemukan!</h5>
                            </div>
                        @endif
                    </div>
                    
                </form>
            </div>
                
            </div>
            
        </div>
    </section>
    <!--End Donate Section-->
    
@endsection
