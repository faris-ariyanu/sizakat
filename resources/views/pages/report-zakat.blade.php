@extends('layouts.index')

@section('style')
<style>
    .title{
        font-size: 23px;
        line-height: 30px;
        text-align: center;
        background: #0d3f51;
        padding: 15px;
        color: #ffff;
        margin-bottom: 10px;
    }
    .table{
        font-size: 20px;
    }
</style>
@endsection
@section('content')

    <!--Donate Section-->
    <section class="donate-section" style="margin-top:15px;" id="app">
    	<div class="auto-container">
            <div class="donate-form-section">
                <div class="donation-form-outer col-md-12">
            	    <div class="form-portlet">
                        @if($periode)
                            <div class="title">
                                Panitia Penerimaan Zakat Masjid Baiturrahmah {{$periode->year}} Menerima dan Menyalurkan Zakat Fitrah, Zakat Mal, Fidyah, Infaq / Sedekah
                            </div>
                            <div class="title">
                                Status Penerimaan Zakat {{daynow()}} {{tanggal_indo(date('Y-m-d'))}} <span id="clock"></span> WIB
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Jenis Zakat</th>
                                        <th>Jumlah Zakat</th>
                                        <th>Jumlah Beras</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Zakat Mal</td>
                                        <td>Rp. @{{model.mal.amount | currency}}</td>
                                        <td>0 Kg</td>
                                    </tr>
                                    <tr>
                                        <td>Zakat Fitrah</td>
                                        <td>Rp. @{{model.ftr_uang.amount | currency}}</td>
                                        <td>@{{model.ftr_beras.amount | currency}} Kg</td>
                                    </tr>
                                    <tr>
                                        <td>Fidyah</td>
                                        <td>Rp. @{{model.fdy_uang.amount | currency}}</td>
                                        <td>@{{model.fdy_beras.amount | currency}} Kg</td>
                                    </tr>
                                    <tr>
                                        <td>Infaq Sedekah</td>
                                        <td>Rp. @{{model.sdq_uang.amount | currency}}</td>
                                        <td>@{{model.sdq_beras.amount | currency}} Kg</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total : </th>
                                        <th>Rp. @{{model.total_uang | currency}}</th>
                                        <th>@{{model.total_beras | currency}} Kg</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="title">
                                Total Jiwa:
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Zakat Fitrah</th>
                                        <th>Fidyah</th>
                                        <th>Infaq Sedekah</th>
                                        <th>Zakat Mal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@{{model.ftr_beras.total + model.ftr_uang.total}} Orang</td>
                                        <td>@{{model.fdy_beras.total + model.fdy_uang.total}} Orang</td>
                                        <td>@{{model.sdq_beras.total + model.sdq_uang.total}} Orang</td>
                                        <td>@{{model.mal.total}} Orang</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Donate Section-->
    
@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script src="https://unpkg.com/vue-currency-filter"></script>
<script>
    Vue.use(VueCurrencyFilter,{
        symbol : '',
        thousandsSeparator: '.',
        fractionCount: 1,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    });

    var app = new Vue({
        el: '#app',
        data: {
            model : {
                ftr_beras : {},
                ftr_uang : {},
                fdy_beras : {},
                fdy_uang : {},
                mal : {},
                sdq_beras : {},
                sdq_uang : {},
                total_beras : 0,
                total_uang : 0,
            }
        },
        created(){
            let _ = this;
            _.getReport();
            setInterval(function () {
                _.getReport();
            }, 5000);
            _.startTime();
        },
        methods : {
            getReport(){
                axios.get("{{url('api/v1/report-dashboard')}}",{
                    params : {periode : '{{$periode->year}}'}
                })
                .then(res => {
                    if (res.data.status) {
                        this.model = res.data.data;
                    }
                });
            },
            startTime(){
                let today = new Date();
                let h = today.getHours(),
                    m = today.getMinutes(),
                    s = today.getSeconds();
                m = this.checkTime(m);
                s = this.checkTime(s);
                document.getElementById('clock').innerHTML =
                h + ":" + m + ":" + s;
                var t = setTimeout(this.startTime, 500);
            },
            checkTime(i) {
                if (i < 10) {i = "0" + i};
                return i;
            }
        }
    });
</script>
@endsection
