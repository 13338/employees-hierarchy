<?php

namespace App\Http\Controllers;

use App\Employee;
use App\QueryFilters\EmployeeFilters;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['home']);
    }

    /**
     * Display a tree of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        if (request()->wantsJson()) {
            // $employees = Employee::with(['subordinates' => function ($query) {
            //     $query->count();
            // }])
            $employees = Employee::with(['subordinates'])
                ->orderBy('id')
                ->where('director_id', request()->branch)
                ->paginate(5);
            return $employees;
        } else {
            $employees = Employee::orderBy('id')
                ->where('director_id', null)
                ->orWhere('director_id', 0)
                ->paginate(5);
            return view('employees', compact(['employees']));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeFilters $filters)
    {
        $employees = Employee::sortable()
            ->filterBy($filters)
            ->paginate(50);
        if (request()->wantsJson()) {
            return $employees;
        } else {
            return view('employees.index', compact(['employees']));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!($director = Employee::find($request->director_id))) { // Director
            $request->merge(['director_id' => null]);
        }
        $id = Employee::create($request->all());
        $request->session()->flash('status', 'Employee created!');
        return redirect()->route('employees.show', ['employee' => $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact(['employee']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact(['employee']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        if (!($director = Employee::find($request->director_id))) { // Director
            $request->merge(['director_id' => null]);
        }
        $employee->update($request->all());
        if (request()->wantsJson()) {
            return $employee;
        } else {
            $request->session()->flash('status', 'Employee updated!');
            return redirect()->route('employees.show', ['employee' => $employee->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        if ($employee->delete()) {
            return response()->json([
                'success' => 'Employee deleted!',
                'employee' => $employee
            ]);
        } else {
            return response()->json(['error' => 'Not deleted!'], 403);
        }
    }
}
