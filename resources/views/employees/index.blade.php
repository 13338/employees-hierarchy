@extends('layouts.app')

@section('content')
<div class="container-fluid" id="container">
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr class="">
					<th data-sort="id">
						@sortablelink('id', __('id'))
					</th>
					<th data-sort="director_id">
						<abbr title="{{ __('ID Руководителя') }}">@sortablelink('director_id', __('Рук.'))</abbr>
					</th>
					<th data-sort="fio">
						@sortablelink('fio', __('ФИО'))
					</th>
					<th data-sort="position">
						@sortablelink('position', __('Должность'))
					</th>
					<th data-sort="employment_at">
						@sortablelink('employment_at', __('Дата приема'))
					</th>
					<th data-sort="wages">
						@sortablelink('wages', __('Зарплата'))
					</th>
					<th data-sort="updated_at">
						@sortablelink('updated_at', __('Дата редактирования'))
					</th>
					<th data-sort="created_at">
						@sortablelink('created_at', __('Дата создания'))
					</th>
					<th>
						{{ __('Ссылки') }}
					</th>
				</tr>
			</thead>
			<tbody id="employees">
				<tr>
					<td><input type="number" class="form-control form-control-sm" form="search" name="id" value="{{ app('request')->input('id') }}" min="{{ App\Employee::min('id') }}" max="{{ App\Employee::max('id') }}" step="1"></td>
					<td><input type="number" class="form-control form-control-sm" form="search" name="director" value="{{ app('request')->input('director') }}" min="{{ App\Employee::min('director_id') }}" max="{{ App\Employee::max('director_id') }}" step="1"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="fio" value="{{ app('request')->input('fio') }}"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="position" value="{{ app('request')->input('position') }}"></td>
					<td><input type="date" class="form-control form-control-sm" form="search" name="employment" value="{{ app('request')->input('employment') }}"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="wages" value="{{ app('request')->input('wages') }}"></td>
					<td><input type="datetime-local" class="form-control form-control-sm" form="search" name="updated" value="{{ app('request')->input('updated') }}" step="1"></td>
					<td><input type="datetime-local" class="form-control form-control-sm" form="search" name="created" value="{{ app('request')->input('created') }}" step="1"></td>
					<td>
						<form id="search">
							<button class="btn btn-success btn-block btn-sm" type="submit">{{ __('Поиск') }}</button>
						</form>
					</td>
				</tr>
				@forelse ($employees as $employee)
				<tr class="employee">
					<td>{{ $employee->id }}</td>
					<td>{{ $employee->director_id ?: '-' }}</td>
					<td>
						<a href="{{ route('employees.show', ['employee' => $employee->id]) }}">
							<img src="{{ asset('storage/'.($employee->avatar ?: 'default-50x50.gif')) }}" class="rounded float-left mr-2 border" alt="{{ __('Фотография') }}" width="50" height="50">
							{{ $employee->fio }}
						</a>
					</td>
					<td>{{ $employee->position }}</td>
					<td>{{ $employee->employment_at }}</td>
					<td>{{ $employee->wages }}</td>
					<td>{{ $employee->updated_at }}</td>
					<td>{{ $employee->created_at }}</td>
					<td class="py-0 align-middle">
						<div class="btn-group btn-group-sm d-flex" role="group" aria-label="Basic example">
							<a href="{{ route('employees.show', ['employee' => $employee->id]) }}" class="btn btn-outline-primary w-100" title="{{ __('Просмотр') }}"><i class="fa fa-eye"></i></a>
							<a href="{{ route('employees.edit', ['employee' => $employee->id]) }}" class="btn btn-outline-warning w-100" title="{{ __('Редактировать') }}"><i class="fa fa-edit"></i></a>
							<button type="button" class="btn btn-outline-danger w-100 delete" data-id="{{ $employee->id }}" data-href="{{ route('employees.destroy', ['employee' => $employee->id]) }}" title="{{ __('Удалить') }}"><i class="fa fa-trash-o"></i></button>
						</div>
					</td>
				</tr>
				@empty
				<tr class="table-warning text-center employee">
					<td colspan="8">{{ __('Результатов нет') }}</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
	{{ $employees->appends(request()->input())->links('vendor/pagination/bootstrap-4') }}
</div>
@endsection

@section('js')
	<script type="text/javascript">
		// ajax sort, seach
		$('#search button, th a').click(function(event) {
			event.preventDefault();
			$('th i').removeClass('fa-sort-desc fa-sort-asc').addClass('fa-sort');
			// sort
			if ($(event.target).is('th a')) {
				var th 	   = $(this).parent();
				var sort   = th.data('sort');
				var order  = 'asc';

				if (th.data('order')) {
					if (th.data('order') == 'asc') {
						th.find('i').removeClass('fa-sort fa-sort-asc').addClass('fa-sort-desc');
						th.data('order', 'desc');
					} else {
						th.find('i').removeClass('fa-sort fa-sort-desc').addClass('fa-sort-asc');
						th.data('order', 'asc');
					}
					order = th.data('order');
				} else {
					th.find('i').removeClass('fa-sort').addClass('fa-sort-asc');
					th.data('order', order);
				}
			}

			$.ajax({
				type: 'GET',
				dataType: 'json',
				data: {
					// search data
					id: 		$('input[name=id]').val(),
					director: 	$('input[name=director]').val(),
					fio: 		$('input[name=fio]').val(),
					position: 	$('input[name=position]').val(),
					employment: $('input[name=employment]').val(),
					wages: 		$('input[name=wages]').val(),
					updated: 	$('input[name=updated]').val(),
					created: 	$('input[name=created]').val(),
					// sort data
					sort: 		(typeof sort  !== 'undefined') ? sort  : null,
					order: 		(typeof order !== 'undefined') ? order : 'asc',
				},
			})
			.done(function(result) {
				$('#employees .employee, .pagination').empty(); // remove old result
				// append results
				if (result.total > 0) {
		        	$.each(result.data, function(index, element) {
			            $('#employees').append(`
			            <tr class="employee">
							<td>${element.id}</td>
							<td>${(element.director_id === null) ? '-' : element.director_id}</td>
							<td>
								<a href="{{ route('employees.index') }}/${element.id}">
									<img src="{{ asset('storage') }}/${((element.avatar === null) ? 'default-50x50.gif' : element.avatar)}" class="rounded float-left mr-2 border" alt="{{ __('Фотография') }}" width="50" height="50">
									${element.fio}
								</a>
							</td>
							<td>${element.position}</td>
							<td>${element.employment_at}</td>
							<td>${element.wages}</td>
							<td>${element.updated_at}</td>
							<td>${element.created_at}</td>
							<td class="py-0 align-middle">
								<div class="btn-group btn-group-sm d-flex" role="group" aria-label="Basic example">
									<a href="{{ route('employees.index') }}/${element.id}" class="btn btn-outline-primary w-100" title="{{ __('Просмотр') }}"><i class="fa fa-eye"></i></a>
									<a href="{{ route('employees.index') }}/${element.id}/edit" class="btn btn-outline-warning w-100" title="{{ __('Редактировать') }}"><i class="fa fa-edit"></i></a>
									<button type="button" class="btn btn-outline-danger w-100 delete" data-id="${element.id}" data-href="{{ route('employees.index') }}/${element.id}" title="{{ __('Удалить') }}"><i class="fa fa-trash-o"></i></button>
								</div>
							</td>
						</tr>`);
			        });
		        } else { // if 0 results
		        	$('#employees').append('<tr class="table-warning text-center employee"><td colspan="8">{{ __('Результатов нет') }}</td></tr>');
		        }
			})
			.fail(function(jqXHR, status) {
				alert(jqXHR.responseJSON.error);
			});
		});
		// delete employee
		$('body').on('click', '.delete', function(event) {
			event.preventDefault();
			var element = $(this);
			var url 	= element.data('href')
			if (confirm("Delete ?")) {
				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: { _method: 'delete'},
				})
				.done(function() {
					element.closest('tr').addClass('table-danger').fadeOut('1000', function() {
						$(this).remove();
					}); 
				})
				.fail(function(jqXHR, status) {
					alert(jqXHR.responseJSON.error);
				});
			}
		});
	</script>
@endsection