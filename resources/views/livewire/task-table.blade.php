@extends('components.layouts.app')

@section('content')
<div class="table-container">
    <h1>Task Table</h1>
    <!-- Create Button -->
   <div style="text-align: right; padding-bottom:10px;">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            Create
        </button>
   </div>

    <!-- Task Table -->
    <table id="tasksTable" class="table table-striped custom-table" style="padding-bottom:10px;padding-top:10px;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taskAttributes as $task)
                <tr>
                    <td>{{ $task['title'] }}</td>
                    <td class="status-cell">{{ ucfirst($task['status']) }}</td>
                    <td>{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d') }}</td>
                    <td>
                        <button 
                            class="btn btn-primary mark-completed" 
                            data-id="{{ $task['id'] }}" 
                            {{ $task['status'] === 'Completed' ? 'disabled' : '' }}
                        >
                            {{ $task['status'] === 'Completed' ? 'Completed' : 'Mark as Completed' }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No tasks available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    {{ $tasks->links() }}
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Title Field -->
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="taskTitle" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div class="mb-3">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="taskStatus" name="status" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Due Date Field -->
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="taskDueDate" name="due_date" value="{{ old('due_date') }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tasksTable').DataTable({
                "pageLength": 10,
                "autoWidth": false
            });

            // Attach click event to Mark as Completed button
            $(document).on('click', '.mark-completed', function() {
                var taskId = $(this).data('id');
                var row = $(this).closest('tr');  // Get the row that contains the button
                console.log("Sending AJAX");
                // Show confirmation alert
                if (confirm("Are you sure you want to mark this task as completed?")) {
                    // Log to check the taskId being sent
                    console.log("Sending AJAX request to mark task as completed. Task ID: " + taskId);
                    
                    // Make an AJAX request to update the task status
                    $.ajax({
                        url: '/tasks/' + taskId + '/complete',  // Update with your route
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",  // CSRF token for security
                        },
                        success: function(response) {
                            // Log the server's response
                            console.log("Response from server:", response);
                            
                            if (response.success) {
                                // Update the status in the table
                                row.find('.status-cell').text('Completed');
                                // Disable the button after successful update
                                row.find('.mark-completed').prop('disabled', true).text('Completed');
                            } else {
                                console.log('Failed to update task status');
                                alert('Failed to update task status');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Log the error if the AJAX request fails
                            console.log("AJAX request failed with status: " + status);
                            console.log("Error message: " + error);
                            alert('Error occurred while updating the task status');
                        }
                    });
                }
            });
        });
    </script>
@endpush
