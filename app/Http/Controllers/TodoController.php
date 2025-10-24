<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        //validate with custom validation in form request
        $validated = $request->validated();

        //store data in db
        $todo = Todo::create($validated);

        //return a structured response
        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully.',
            'data' => $todo,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        //
    }

    public function excel(Request $request)
    {
        $todoQuery = Todo::query()->filter($request->all())
        ->get();
        // return $todoQuery;
        return Excel::download(new TodosExport($todoQuery),'export-todos-'.now()->toDateString().'.xlsx');
    }

    public function summary(Request $request) {
        $fields = (new Todo)->getFillable();
        $rm = 'title';
        $newFields = array_diff($fields, [$rm]);
        array_push($newFields,'keyword');

        if (empty($request->query('type'))) {
            return response()->json([
                'status' => "error",
                'message' => "parameter 'type' cannot be empty"
            ],400);
        }
        if (!in_array($request->query('type'),$newFields)) {
            $fd = implode(', ',$newFields);
            return response()->json([
                'status' => "error",
                'message' => "parameter 'type' cannot be other than availables : ". $fd
            ],400);
        }
        $todo = Todo::getSummary($request->query('type')); //
        return response()->json($todo);
    }
}
