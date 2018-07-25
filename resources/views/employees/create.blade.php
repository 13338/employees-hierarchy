@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
        <div class="col-md-8 my-5">
        	<h1>{{ __('Создать сотрудника') }}</h1>
        	<hr>
			<form action="{{ route('employees.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="director_id">{{ __('Руководитель') }}</label>
                    <select name="director_id" class="form-control" id="director_id">
                    	@if (app('request')->exists('director_id') && $director = App\Employee::find(app('request')->input('director_id')))
                    		<option value="{{ $director->id }}" selected="selected">{{ $director->fio }}</option>
                    	@else
                    		<option value="-1" selected="selected">{{ __('Без руководителя') }}</option>
                    	@endif
                    </select>
                </div>
                <hr class="form-divider">
                <div class="form-group">
                	<label for="fio">{{ __('ФИО') }}</label>
                	<input name="fio" type="text" class="form-control" id="fio" placeholder="{{ __('Фамилия, имя, отчество') }}" value="{{ old('fio') }}">
                </div>
                <hr class="form-divider">
                <div class="form-group">
                	<label for="position">{{ __('Должность') }}</label>
                	<input name="position" type="text" class="form-control" id="position" placeholder="{{ __('Должность') }}" value="{{ old('position') }}">
                </div>
                <hr class="form-divider">
                <div class="form-group">
                	<label for="employment_at">{{ __('Дата приема') }}</label>
                	<input name="employment_at" type="date" class="form-control" id="employment_at" placeholder="{{ __('Дата приема') }}" value="{{ old('employment_at') or date('Y-m-d') }}">
                </div>
                <hr class="form-divider">
                <div class="form-group">
                	<label for="wages">{{ __('Зарплата') }}</label>
                	<input name="wages" type="text" class="form-control" id="wages" placeholder="{{ __('Зарплата') }}" value="{{ old('wages') }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="float-right btn btn-success">{{ __('Создать') }}</button>
                </div>
            </form>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('#director_id').select2({
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
</script>
@endsection