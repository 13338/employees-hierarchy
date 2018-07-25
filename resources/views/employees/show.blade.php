@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
        <div class="col-md-8 my-5">
        	<h1>{{ $employee->fio }}</h1>
        	<hr>
        	<div class="card">
        		<div class="card-body">
        			<table class="table table-striped table-hover table-bordered">
        				<thead>
        					<tr>
        						<th>{{ __('Свойство') }}</th>
        						<th>{{ __('Значение') }}</th>
        					</tr>
        				</thead>
        				<tbody>
        					<tr>
        						<td>Руководитель</td>
        						<td>{{ $employee->director->fio or '-' }}</td>
        					</tr>
        					<tr>
        						<td>ФИО</td>
        						<td>{{ $employee->fio }}</td>
        					</tr>
        					<tr>
        						<td>Должность</td>
        						<td>{{ $employee->position }}</td>
        					</tr>
        					<tr>
        						<td>Дата приема</td>
        						<td>{{ $employee->employment_at }}</td>
        					</tr>
        					<tr>
        						<td>Зарплата</td>
        						<td>{{ $employee->wages }}</td>
        					</tr>
        				</tbody>
        			</table>
        			<div class="float-right">
        				<a href="{{ route('employees.edit', ['employee' => $employee->id]) }}" class="btn btn-outline-warning" title="{{ __('Редактировать') }}"><i class="fa fa-edit"></i></a>
                    	<button type="button" class="btn btn-outline-danger delete" data-id="{{ $employee->id }}" title="{{ __('Удалить') }}"><i class="fa fa-trash-o"></i></button>
        			</div>
        		</div>
        	</div>
		</div>
	</div>
</div>
@endsection

@section('js')

@endsection