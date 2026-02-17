<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::paginate(10);

        return response()->json($tasks, 200);
    }

    /**
     * По сути этот метод нужен только если используем blade, я реализовывал API
     * поэтому считаю его лишним
     *
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = Task::create($data);

        return response()->json($task, 201);
    }

    /**
     * Но тут можно проще, ларка нам поможет
     *
     * public function show(Task $task)
     * {
     *     return response()->json($task, 200);
     * }
     *
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task, 200);
    }

    /**
     * По сути этот метод нужен только если используем blade, я реализовывал API
     * поэтому считаю его лишним
     *
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tasks = Task::findOrFail($id);

        return response()->json($tasks, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->noContent();
    }
}
