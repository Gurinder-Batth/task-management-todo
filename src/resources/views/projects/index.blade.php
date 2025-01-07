@extends('layouts.app')

@section('content')
<div class=" mb-3">
    <h1>Project Manager</h1>

    <a href="{{route('tasks.index')}}" class="btn btn-success">Add Task</a>
    <!-- Button to trigger Add Project modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add Project</button>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Project List -->
<ul class="list-group">
    @forelse ($projects as $project)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $project->name }}</span>
            <div>
                <!-- Edit Button -->
                <button 
                    class="btn btn-sm btn-warning me-2" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editProjectModal{{ $project->id }}">Edit</button>

                <!-- Delete Form -->
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">
                        Delete
                    </button>
                </form>
            </div>
        </li>

        <!-- Edit Project Modal -->
        <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-labelledby="editProjectModalLabel{{ $project->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProjectModalLabel{{ $project->id }}">Edit Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Validation Errors -->
                            @if ($errors->has('name') && old('id') == $project->id)
                                <div class="alert alert-danger">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="project-name-{{ $project->id }}" class="form-label">Project Name</label>
                                <input type="text" id="project-name-{{ $project->id }}" name="name" class="form-control" value="{{ $project->name }}" required>
                                <input type="hidden" name="id" value="{{ $project->id }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <li class="list-group-item text-center">No projects found. Start by adding a new project!</li>
    @endforelse
</ul>

<!-- Pagination Links -->
<div class="mt-3">
    {{ $projects->links() }}
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Validation Errors -->
                    
                    <div class="mb-3">
                        <label for="project-name" class="form-label">Project Name</label>
                        <input type="text" id="project-name" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
