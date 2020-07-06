<html>
    <body>
        <table>
            <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Muzakki</th>
                <th>Jenis Zakat</th>
                <th>Jumlah Nominal</th>
                <th>Jumlah Barang</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            @foreach($zakat as $row)
                <tr>
                    <td>{{$row->transaction_number}}</td>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->muzakki_name }}</td>
                    <td>{{ $row->zakat_type }}</td>
                    <td>{{ $row->income_value }}</td>
                    <td>{{ $row->income_goods }} Kg</td>
                </tr>
                <?php $i++; ?>
            @endforeach
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td colspan="2">Rekapitulasi Laporan</td>
                </tr>   
                <tr>
                    <td>Fitrah Uang</td>
                    <td>: {{number($rekap['ftr_uang']['amount'],1)}}</td>
                </tr>   
                <tr>
                    <td>Fitrah Beras</td>
                    <td>: {{number($rekap['ftr_beras']['amount'])}} Kg</td>
                </tr> 
                <tr>
                    <td>Fidyah Uang</td>
                    <td>: {{number($rekap['fdy_uang']['amount'],1)}}</td>
                </tr>   
                <tr>
                    <td>Fidyah Beras</td>
                    <td>: {{number($rekap['fdy_beras']['amount'])}} Kg</td>
                </tr>  
                <tr>
                    <td>Mal</td>
                    <td>: {{number($rekap['mal']['amount'],1)}}</td>
                </tr>   
                <tr>
                    <td>Infaq Uang</td>
                    <td>: {{number($rekap['sdq_uang']['amount'],1)}}</td>
                </tr>   
                <tr>
                    <td>Infaq Beras</td>
                    <td>: {{number($rekap['sdq_beras']['amount'])}} Kg</td>
                </tr>  
                <tr>
                    <td>Jumlah Transaksi Muzakki Mal</td>
                    <td>: {{number($rekap['mal']['total'])}} Orang</td>
                </tr>  
                <tr>
                    <td>Jumlah Transaksi Muzakki Infaq</td>
                    <td>: {{number(($rekap['sdq_uang']['total'] + $rekap['sdq_beras']['total']))}} Orang</td>
                </tr>   
                <tr>
                    <td>Total Uang</td>
                    <td>: {{number($rekap['total_uang'],1)}}</td>
                </tr> 
                <tr>
                    <td>Total Uang</td>
                    <td>: {{number($rekap['total_beras'])}} Kg</td>
                </tr>  
                <tr>
                    <td>Total Muzakki yang Bertransaksi</td>
                    <td>: {{number($rekap['total_muzakki'])}} Orang</td>
                </tr>    
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td colspan="6" align="center">Mengetahui</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">Operator</td>
                    <td colspan="2" align="center">Supervisor</td>
                    <td colspan="2" align="center">Pengurus</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">(                 )</td>
                    <td colspan="2" align="center">(                 )</td>
                    <td colspan="2" align="center">(                 )</td>
                </tr>
            </tbody>
        <table>
    </body>
</html>