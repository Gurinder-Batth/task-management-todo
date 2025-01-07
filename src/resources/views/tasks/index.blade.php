@extends('layouts.app')

@section('content')
<div class="  mb-3">
    <h1>Task Manager</h1>
    <a href="{{route('projects.index')}}" class="btn btn-success">Add Project</a>
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add Task</a>
</div>

<ul id="task-list" class="list-group">
    @foreach($tasks as $task)
    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
        <span>{{ $task->name }}</span>
        <div>
            <button class="btn btn-sm btn-warning edit-task-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-project-id="{{ $task->project_id }}">Edit</button>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    </li>
    @endforeach
</ul>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="editTaskForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-task-name" class="form-label">Task Name</label>
                        <input type="text" id="edit-task-name" name="name" class="form-control" required>
                    </div>
                    <input type="hidden" id="edit-task-id" name="task_id">
                    <input type="hidden" id="edit-project-id" name="project_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="task-name" class="form-label">Task Name</label>
                        <input type="text" id="task-name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="project-id" class="form-label">Project</label>
                        <select id="project-id" name="project_id" class="form-select" required>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
    // JavaScript to dynamically populate the Edit Modal with the task data
    document.querySelectorAll('.edit-task-btn').forEach(button => {
        button.addEventListener('click', function () {
            const taskId = this.getAttribute('data-id');
            const taskName = this.getAttribute('data-name');
            const projectId = this.getAttribute('data-project-id');

            document.getElementById('edit-task-id').value = taskId;
            document.getElementById('edit-task-name').value = taskName;
            document.getElementById('edit-project-id').value = projectId;

            document.getElementById('editTaskForm').action = '/tasks/' + taskId;

            const projectSelect = document.getElementById('project-id');
            for (let i = 0; i < projectSelect.options.length; i++) {
                if (projectSelect.options[i].value == projectId) {
                    projectSelect.selectedIndex = i;
                    break;
                }
            }
        });
    });

    // Initialize SortableJS for task list
    const taskList = document.getElementById('task-list');
    const sortable = new Sortable(taskList, {
        onEnd: function (evt) {
            const reorderedTaskIds = [];
            taskList.querySelectorAll('li').forEach(item => {
                reorderedTaskIds.push(item.getAttribute('data-id'));
            });

            // Send the reordered task IDs to the server via AJAX
            fetch("{{ route('tasks.reorder') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tasks: reorderedTaskIds
                })
            }).then(response => response.json())
              .then(data => {
                  console.log(data); // You can handle the success response here
              })
              .catch(error => {
                  console.error('Error:', error);
              });
        }
    });
</script>
@endsection
