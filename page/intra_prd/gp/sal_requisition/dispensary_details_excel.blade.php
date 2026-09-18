<table>
    <thead>
        <tr>
            <th>GP Name</th>
            <th>GP Code</th>
            <th>Block Name</th>
            @foreach ($medicineList as $medicine)
                <th>{{ $medicine->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($gpNames as $gp)
            <tr>
                <td>{{ $gp['gp_name'] }}</td>
                <td>{{ $gp['gp_code'] }}</td>
                <td>{{ $gp['block_name'] }}</td>
                @foreach ($medicineList as $medicine)
                    <td>{{ $gpMedicineStock[$gp['gp_code']][$medicine->id] ?? 0 }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>




