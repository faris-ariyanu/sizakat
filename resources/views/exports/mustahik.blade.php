<html>
    <body>
        <table>
            <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>NO KTP</th>
                <th>NO TELP</th>
                <th>ALAMAT</th>
                <th>RT</th>
                <th>RW</th>
                <th>TGL LAHIR</th>
                <th>STATUS</th>
                <th>JML KELUARGA</th>
                <th>KET</th>
                <th>NOMINAL(RP)</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            @foreach($mustahik as $mus)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ strtoupper($mus->name) }}</td>
                    <td>'{{ strtoupper($mus->no_ktp) }}</td>
                    <td>'{{ strtoupper($mus->phone) }}</td>
                    <td>{{ strtoupper($mus->address) }}</td>
                    <td>{{ strtoupper($mus->rt) }}</td>
                    <td>{{ strtoupper($mus->rw) }}</td>
                    <td>{{ strtoupper($mus->tgl_lahir) }}</td>
                    <td>{{ strtoupper($mus->mustahik_status) }}</td>
                    <td>{{ strtoupper($mus->family_size) }}</td>
                    <td>{{ strtoupper($mus->description) }}</td>
                    <td>{{$mus->category->value}}</td>
                </tr>
                <?php $i++; ?>
            @endforeach
            </tbody>
        </table>
    </body>
</html>