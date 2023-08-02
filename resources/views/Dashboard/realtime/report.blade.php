<table class="table table-borderless" id="myTable">
	<thead>
		<tr>
			<th>No</th>
			<th>Status</th>
			<th>Date & Time</th>
		</tr>
	</thead>
	<tbody>
        @php $no=1; @endphp
		@foreach($report as $data)
			<tr>
                <td>{{ $no++ }}</td>
				<td>{!!  $data[text] ?? 'uknown' !!}</td>
				<td>{{ $carbon::parse($data['created_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y, h:i A') }} </td>
			</tr>
		@endforeach
	</tbody>
</table>
