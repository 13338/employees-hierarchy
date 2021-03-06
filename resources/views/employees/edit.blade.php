@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
        <div class="col-md-8 my-5">
        	<h1>{{ __('Изменение сотрудника') }} ID{{ $employee->id }}</h1>
        	<hr>
        	<ul class="nav nav-tabs nav-fill mb-4">
        		<li class="nav-item">
        			<a class="nav-link active" id="edit-tab" data-toggle="tab" href="#edit">{{ __('Изменение информации') }}</a>
        		</li>
        		<li class="nav-item">
        			<a class="nav-link" id="redistribution-tab" data-toggle="tab" href="#redistribution" title="{{ __('Перераспределение подчиненных') }}">{{ __('Замена сотрудника') }}</a>
        		</li>
        	</ul>
        	<div class="tab-content">
        		<div class="tab-pane show active" id="edit">
        			<form action="{{ route('employees.update', ['employee' => $employee->id]) }}" method="POST" enctype="multipart/form-data">
		                {{ csrf_field() }}
		                {{ method_field('PATCH') }}
		                <div class="form-group">
		                    <label for="director_id">{{ __('Руководитель') }}</label>
		                    <select name="director_id" class="form-control" id="director_id">
		                    	<option value="{{ $employee->director_id or '-1' }}" selected="selected">{{ $employee->director->fio or __('Без руководителя') }}</option>
		                    </select>
		                </div>
		                <hr class="form-divider">
		                <div class="form-group">
		                	<label for="avatar">{{ __('Фотография') }}</label>
		                	<div id="current_avatar">
		                		<img src="{{ asset('storage/'.($employee->avatar ?: 'default-50x50.gif')) }}" class="rounded float-left mr-2 mb-2 border" alt="{{ __('Фотография') }}" width="50" height="50">
		                		@if ($employee->avatar)
		                		<h1><a href="#" data-href="{{ route('employees.update', ['employee' => $employee->id]) }}" class="text-danger" id="delete_avatar"><i class="fa fa-trash-o"></i></a></h1>
		                		@endif
		                	</div>
		            		<input name="avatar" type="file" class="form-control" id="avatar" placeholder="{{ __('Фотография') }}" value="{{ old('avatar') }}">
		                </div>
		                <hr class="form-divider">
		                <div class="form-group">
		                	<label for="fio">{{ __('ФИО') }}</label>
		                	<input name="fio" type="text" class="form-control" id="fio" placeholder="{{ __('Фамилия, имя, отчество') }}" value="{{ old('fio') ?: $employee->fio }}">
		                </div>
		                <hr class="form-divider">
		                <div class="form-group">
		                	<label for="position">{{ __('Должность') }}</label>
		                	<input name="position" type="text" class="form-control" id="position" placeholder="{{ __('Должность') }}" value="{{ old('position') ?: $employee->position }}">
		                </div>
		                <hr class="form-divider">
		                <div class="form-group">
		                	<label for="employment_at">{{ __('Дата приема') }}</label>
		                	<input name="employment_at" type="date" class="form-control" id="employment_at" placeholder="{{ __('Дата приема') }}" value="{{ old('employment_at') ?: $employee->employment_at }}">
		                </div>
		                <hr class="form-divider">
		                <div class="form-group">
		                	<label for="wages">{{ __('Зарплата') }}</label>
		                	<input name="wages" type="text" class="form-control" id="wages" placeholder="{{ __('Зарплата') }}" value="{{ old('wages') ?: $employee->wages }}">
		                </div>
		                <div class="form-group">
		                    <button type="submit" class="float-right btn btn-primary">{{ __('Сохранить') }}</button>
		                </div>
		            </form>
        		</div>
        		<div class="tab-pane" id="redistribution">
        			<form action="{{ route('employees.update', ['employee' => $employee->id]) }}" method="POST">
		                {{ csrf_field() }}
		                {{ method_field('PATCH') }}
		                <div class="form-group">
		                    <label for="new_director_id">{{ __('Замена на') }}</label>
		                    <select name="new_director_id" class="form-control" id="new_director_id" style="width: 100%">
		                    	<option value="{{ $employee->id or '-1' }}" selected="selected">{{ $employee->fio or __('Без руководителя') }}</option>
		                    </select>
		                    <small class="text-muted">
								{{ __('Все подчиненные будут перераспределены') }}
							</small>
		                </div>
		                <hr class="form-divider">
		                <div class="custom-control custom-checkbox">
		                	<input type="checkbox" class="custom-control-input" name="delete" id="customCheck1">
		                	<label class="custom-control-label" for="customCheck1">{{ __('Удалить текущего сотрудника') }}</label>
		                </div>
		                <div class="form-group">
		                    <button type="submit" class="float-right btn btn-primary">{{ __('Заменить') }}</button>
		                </div>
		            </form>
        		</div>
        	</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	// ajax select for director field
	$('#director_id, #new_director_id').select2({
		theme: 'bootstrap4',
		ajax: {
			url: '{{ route('employees.index') }}',
			dataType: 'json',
			delay: 50,
			allowClear: true,
			// language: '{{ app()->getLocale() }}',
			data: function (params) {
				return {
					fio: params.term, // search term
					page: params.page
				};
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.data,
					pagination: {
						more: (params.page * 50) < data.total
					}
				};
			},
			cache: true
		},
		placeholder: '{{ __('Выберете руководителя') }}',
		escapeMarkup: function (markup) { return markup; },
		minimumInputLength: 2,
		templateResult: formatRepo,
		templateSelection: formatRepoSelection
	});

	function formatRepo (repo) {
		if (repo.loading) {
			return repo.text;
		}
		var markup = repo.fio + ' ID' + repo.id;
		return markup;
	}

	function formatRepoSelection (repo) {
		return (repo.fio || repo.text);
	}
	@if ($employee->avatar)
	// delete avatar (set null)
	$('#delete_avatar').click(function(event) {
		event.preventDefault();
		var element = $(this);
			var url 	= element.data('href')
			if (confirm("Delete ?")) {
				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: { _method: 'patch', avatar: null},
				})
				.done(function() {
					$('#current_avatar').remove();
				})
				.fail(function(jqXHR, status) {
					alert(jqXHR.responseJSON.error);
				});
			}
	});
	@endif
</script>
@endsection