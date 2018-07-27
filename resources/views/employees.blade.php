@php
$traverse = function ($employees, $count = 2, $first = true) use (&$traverse) {
	echo '<table class="table table-hover border-left">';
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
		if (count($employee->subordinates) > 0) {
    		echo '<tr class="branch-'.$employee->id.'">
				<td>'.$employee->id.'</td>
				<td>'.$employee->fio.'</td>
				<td>'.$employee->position.(($count == 1) ? ' <a href="#" data-branch="'.$employee->id.'" class="branch float-right"><i class="fa fa-plus"></i></a>' : '').'</td>
			</tr>';
			if ($count != 1) {
				echo '<tr>
					<td></td>
					<td colspan="2">';
				$traverse($employee->subordinates, ($count - 1), false);
				echo '</td></tr>';
			}
    	} else {
    		echo '<tr class="branch-'.$employee->id.'">
				<td>'.$employee->id.'</td>
				<td>'.$employee->fio.'</td>
				<td>'.$employee->position.'</td>
			</tr>';
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
	$traverse($employees);
	@endphp
	{{ $employees->links('vendor/pagination/bootstrap-4') }}
</div>

@endsection

@section('js')
<script type="text/javascript">
	$('body').on('click', '.branch', function(event) {
		event.preventDefault();
		var branch = $(this).data('branch');
		$.ajax({
			url: '{{ route('home') }}',
			type: 'GET',
			dataType: 'json',
			data: {branch: branch},
		})
		.done(function(result) {
			// $(event.target).toggleClass('fa-plus fa-minus');
			$(event.target).hide();
			var html = `
			<tr class="open-branch-${branch}">
				<td></td>
				<td colspan="2">
					<table class="table table-hover border-left">
						<tbody>`;
			$.each(result.data, function(index, element) {
				html += 	`<tr class="branch-${element.id}">
								<td>${element.id}</td>
								<td>${element.fio}</td>
								<td>${element.position}</td>
							</tr>`;
				if (element.count_subordinates > 0) {
					html +=	`<tr class="open-branch-${branch}">
								<td>
								</td>
								<td colspan="2">
									<table class="table table-hover border-left">
										<tbody>`;
					$.each(element.subordinates, function(index, element) {
						html += 			`<tr class="branch-${element.id}">
												<td>${element.id}</td>
												<td>${element.fio}</td>
												<td>${element.position}${((element.count_subordinates > 0) ? ' <a href="#" data-branch="' + element.id + '" class="branch float-right"><i class="fa fa-plus"></i></a>' : '')}</td>
											</tr>`;
					});
					html +=				`</tbody>
									</table>
								</td>
							</tr>`;
				}
			});
			html +=		`</tbody>
					</table>
				</td>
			</tr>`;
			$('.branch-' + branch).after(html);
		})
		.fail(function(jqXHR, status) {
            alert(jqXHR.responseJSON.error);
        });
		
	});
</script>
@endsection