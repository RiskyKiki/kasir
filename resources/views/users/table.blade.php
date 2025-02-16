<table id="userTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->username ?? '-' }}</td>
            <td>{{ $user->email ?? '-'}}</td>
            <td>{{ ucfirst($user->role) ?? '-'}}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="showUser({{ $user->id }})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }}, '{{ route('users.destroy', $user->id) }}')"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>