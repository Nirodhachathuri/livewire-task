<?php

// app/Http/Livewire/TaskTable.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Task;
use Livewire\WithPagination;
use Illuminate\Http\Request;


class TaskTable extends Component
{
    use WithPagination;

     // Define the layout here
     protected $layout = 'layouts.app'; // or the correct layout path

     public function index()
    {
        // Get all tasks from the task_manager database
        $tasks = Task::all();
        $taskAttributes = $tasks->map(function ($task) {
            return $task->attributesToArray(); // Convert each Task model to an array of its attributes
        });
        // Return the tasks to a view or return as JSON response
        return view('livewire.task-table', compact('taskAttributes', 'tasks')); // For example
    }
    public function render()
    {
        // Paginate tasks
        $tasks = Task::paginate(10); 
    
        // Convert each task to an array of its attributes
        $taskAttributes = $tasks->map(function ($task) {
            return $task->toArray(); // Convert each Task model to an array of its attributes
        });
    
        if ($tasks->isEmpty()) {
            logger('No tasks found.');
        }
        // Pass taskAttributes and tasks to the view
        return view('livewire.task-table', compact('taskAttributes', 'tasks'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:255', // Allow description to be optional
        ]);

        Task::create($validated);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function markAsCompleted($taskId)
    {
        // Find the task by ID
        $task = Task::find($taskId);

        // Check if the task exists
        if ($task) {
            // Update the task status to 'completed'
            $task->status = 'completed';
            $task->save();

            // Return a response indicating success
            return response()->json(['success' => true]);
        }

        // Return an error response if task not found
        return response()->json(['success' => false]);
    }

    
}
