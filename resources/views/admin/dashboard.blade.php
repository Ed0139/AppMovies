<x-main>

    <h1 class="mb-4">📊 Panel de Administración</h1>

    <!-- CARDS -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Total Usuarios</h5>
                    <h2>{{ $users->count() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Admins</h5>
                    <h2>{{ $users->where('role', 'admin')->count() }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">
                    <h5>Usuarios</h5>
                    <h2>{{ $users->where('role', 'user')->count() }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLA -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Usuarios Registrados
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                @if ($user->role == 'admin')
                                    <span class="badge bg-success">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>

                            <td class="d-flex gap-2">

                                <!-- EDITAR -->
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="role" class="form-select form-select-sm">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User
                                        </option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                    </select>

                                    <button class="btn btn-sm btn-warning mt-1">Guardar</button>
                                </form>

                                <!-- ELIMINAR -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar usuario?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</x-main>
