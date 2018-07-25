<?php

namespace App\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

class EmployeeFilters extends QueryFilters
{

    // id
    public function id($id)
    {
        $this->query->where('id', 'LIKE', $id . '%');
    }

    // id Руководителя
    public function director($director)
    {
        $this->query->where('director_id', '=', $director);
    }

    // ФИО
    public function fio($fio)
    {
        $this->query->where('fio', 'LIKE', '%'. $fio . '%');
    }

    // Должность
    public function position($position)
    {
        $this->query->where('position', 'LIKE', '%'. $position . '%');
    }

    // Дата приема
    public function employment($employment_at)
    {
        $this->query->where('employment_at', '=', $employment_at);
    }

    // Зарплата
    public function wages($wages)
    {
        $this->query->where('wages', $wages);
    }

    // Дата редактирования
    public function updated($updated_at)
    {
        $this->query->where('updated_at', '=', $updated_at);
    }

    // Дата создания
    public function created($created_at)
    {
        $this->query->where('created_at', '=', $created_at);
    }

}
