<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Livewire\TaskTable;
use Illuminate\Http\Request;


Route::get('/tasks', TaskTable::class);

Route::post('/tasks/store', [TaskTable::class, 'store'])->name('tasks.store');

Route::post('/tasks/{task}/complete', [TaskTable::class, 'markAsCompleted']);
