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
				<tr>
					<td><input type="number" class="form-control form-control-sm" form="search" name="id" value="{{ app('request')->input('id') }}" min="{{ App\Employee::min('id') }}" max="{{ App\Employee::max('id') }}" step="1"></td>
					<td><input type="number" class="form-control form-control-sm" form="search" name="director" value="{{ app('request')->input('director') }}" min="{{ App\Employee::min('director_id') }}" max="{{ App\Employee::max('director_id') }}" step="1"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="fio" value="{{ app('request')->input('fio') }}"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="position" value="{{ app('request')->input('position') }}"></td>
					<td><input type="date" class="form-control form-control-sm" form="search" name="employment" value="{{ app('request')->input('employment') }}"></td>
					<td><input type="text" class="form-control form-control-sm" form="search" name="wages" value="{{ app('request')->input('wages') }}"></td>
					<td><input type="datetime-local" class="form-control form-control-sm" form="search" name="updated" value="{{ app('request')->input('updated') }}" step="1"></td>
					<td><input type="datetime-local" class="form-control form-control-sm" form="search" name="created" value="{{ app('request')->input('created') }}" step="1">
						<form id="search">
							<button class="btn btn-outline-success btn-block btn-sm mt-1" type="submit" style="display: none;">Search</button>
						</form>
					</td>
				</tr>
				@forelse ($employees as $employee)
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
				@empty
				<tr class="table-warning text-center">
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
		$('input').focus(function() {
			$('#search button').show();
		});
	</script>
@endsection