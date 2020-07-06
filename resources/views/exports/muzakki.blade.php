<html>
    <body>
        <table>
            <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>NO KTP</th>
                <th>EMAIL</th>
                <th>NO TELP</th>
                <th>ALAMAT</th>
                <th>RT</th>
                <th>RW</th>
                <th>TGL LAHIR</th>
                <th>KET</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            @foreach($muzakki as $muz)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ strtoupper($muz->name) }}</td>
                    <td>'{{ strtoupper($muz->no_ktp) }}</td>
                    <td>{{ strtoupper($muz->email) }}</td>
                    <td>'{{ strtoupper($muz->phone) }}</td>
                    <td>{{ strtoupper($muz->address) }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <?php $i++; ?>
            @endforeach
            </tbody>
        </table>
    </body>
</html>