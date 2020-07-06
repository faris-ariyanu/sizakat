@extends('layouts.index')

@section('content')

    <!--Donate Section-->
    <section class="donate-section" style="margin-top:15px;">
    	<div class="auto-container">
            
            <div class="donate-form-section">
            	
                <div class="donation-form-outer col-md-6">
            	<form method="post">
                	
                    <!--Form Portlet-->
                    <div class="form-portlet">
                        <h3>Cari Data Zakat</h3>
                        
                        <div class="row clearfix">
                            @if ($errors->any())
                                <div class="alert alert-danger col-lg-12 col-md-12 col-xs-12">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{ csrf_field() }}
                            
                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                            	<div class="field-label">No Transaksi <span class="required">*</span></div>
                                <input type="text" name="transaction_number" value="{{old('transaction_number')}}"  placeholder="Masukan No Transaksi" required>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="text-left mb-15">
                        <button type="submit" class="theme-btn btn-style-one btn-block">Cari</button>
                    </div>
                    
                </form>
            </div>
                
            </div>
            
        </div>
    </section>
    <!--End Donate Section-->
    
@endsection
