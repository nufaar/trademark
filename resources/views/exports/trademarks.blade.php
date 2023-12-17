<table>
    <thead>
    <tr>
        <th>Merek</th>
        <th>Pemilik</th>
        <th>Alamat</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($trademarks as $trademark)
        <tr>
            <td>{{ $trademark->name }}</td>
            <td>{{ $trademark->owner }}</td>
            <td>{{ $trademark->address }}</td>
            <td>{{ $trademark->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
