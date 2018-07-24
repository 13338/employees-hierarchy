@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr class="">
					<th>
						@sortablelink('id', __('id'))
					</th>
					<th>
						@sortablelink('director_id', __('ID Руководителя'))
					</th>
					<th>
						@sortablelink('fio', __('ФИО'))
					</th>
					<th>
						@sortablelink('position', __('Должность'))
					</th>
					<th>
						@sortablelink('employment_at', __('Дата приема'))
					</th>
					<th>
						@sortablelink('wages', __('Зарплата'))
					</th>
					<th>
						@sortablelink('updated_at', __('Дата редактирования'))
					</th>
					<th>
						@sortablelink('created_at', __('Дата создания'))
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($employees as $employee)
				<tr>
					<td>{{ $employee->id }}</td>
					<td>{{ $employee->director_id ?: '-' }}</td>
					<td>{{ $employee->fio }}</td>
					<td>{{ $employee->position }}</td>
					<td>{{ $employee->employment_at }}</td>
					<td>{{ $employee->wages }}</td>
					<td>{{ $employee->updated_at }}</td>
					<td>{{ $employee->created_at }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	{{ $employees->appends(request()->input())->links('vendor/pagination/bootstrap-4') }}
</div>
@endsection