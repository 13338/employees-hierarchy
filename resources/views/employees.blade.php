@php
$traverse = function ($employees, $first = false) use (&$traverse) {
	echo '<table class="table table-bordered">';
	if ($first) {
		echo '<thead>
			<tr>
				<th>'.__('id').'</th>
				<th>'.__('ФИО').'</th>
				<th>'.__('Должность').'</th>
			</tr>
		</thead>';
	}
	echo '<tbody>';
    foreach ($employees as $employee) {
        echo '<tr>
				<td>'.$employee->id.'</td>
				<td>'.$employee->fio.'</td>
				<td>'.$employee->position.'</td>
			</tr>';
		if (count($employee->subordinates) > 0) {
			echo '<tr>
				<td></td>
				<td colspan="2">';
			$traverse($employee->subordinates);
			echo '</td></tr>';
		}
        
    }
    echo '</tbody>';
    echo '</table>';
};
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
	@php
	$traverse($employees, true);
	@endphp
	{{ $employees->links('vendor/pagination/bootstrap-4') }}
</div>
@endsection