<x-app-layout>
<div class="container mt-4">
    <h4>Students</h4>

    <!-- Button to open Add Student Modal -->
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#studentModal" 
        onclick="openStudentModal()">+ Add Student</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Route</th>
                <th>Parents</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->full_name }}</td>
                <td>{{ $student->route->name ?? '-' }}</td>
                <td>
                    @foreach($student->parents as $parent)
                        <span class="badge bg-info">{{ $parent->name }}</span>
                    @endforeach
                </td>
                <td>
                    <button class="btn btn-sm btn-warning" 
                        data-bs-toggle="modal" data-bs-target="#studentModal"
                        onclick="openStudentModal({{ $student }})">Edit</button>

                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete student?')">Delete</button>
                    </form>

                    <button class="btn btn-sm btn-info" 
                        data-bs-toggle="modal" data-bs-target="#parentAssignModal"
                        onclick="openParentModal({{ $student }})">Assign Parents</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Student Modal -->
<div class="modal fade" id="studentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="studentForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="student_method" value="POST">

        <div class="modal-header">
          <h5 class="modal-title" id="studentModalTitle">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="mb-2">
                <label>Name</label>
                <input type="text" name="full_name" id="student_name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Route</label>
                <select name="route_id" id="student_route" class="form-control" required>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- Parent Assignment Modal -->
<div class="modal fade" id="parentAssignModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="parentForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="parentModalTitle">Assign Parents</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="mb-2">
                <label>Parents</label>
                <select name="parents[]" id="parent_select" class="form-control">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function openStudentModal(student = null){
    const form = document.getElementById('studentForm');
    const title = document.getElementById('studentModalTitle');
    const methodInput = document.getElementById('student_method');
    const nameInput = document.getElementById('student_name');
    const routeSelect = document.getElementById('student_route');

    if(student){
        title.innerText = "Edit Student";
        form.action = `/students/${student.id}`;
        methodInput.value = "PUT";
        nameInput.value = student.full_name;
        routeSelect.value = student.route_id;
    }else{
        title.innerText = "Add Student";
        form.action = `/students`;
        methodInput.value = "POST";
        nameInput.value = "";
        routeSelect.value = "";
    }
}

function openParentModal(student){
    const form = document.getElementById('parentForm');
    const title = document.getElementById('parentModalTitle');
    const select = document.getElementById('parent_select');

    title.innerText = `Assign Parents for ${student.full_name}`;
    form.action = `/students/${student.id}/parents`;

    // Clear selection
    for(let i=0;i<select.options.length;i++) select.options[i].selected = false;

    // Preselect assigned parents
    if(student.parents){
        student.parents.forEach(p=>{
            const opt = select.querySelector(`option[value='${p.id}']`);
            if(opt) opt.selected = true;
        });
    }
}
</script>

</x-app-layout>
