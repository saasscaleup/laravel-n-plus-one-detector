<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>N+1 Query Detector</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 10px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #ffc107;
        }
        .table-container {
            background-color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>


    <!-- Sidebar -->
    <div class="d-flex">
        <div class="sidebar flex-shrink-0 p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="https://github.com/saasscaleup/laravel-n-plus-one-detector" target="_blank" class="nav-link active">GitHub</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content p-4">
            <h1 class="mb-4">N+1 Query Detector 🕵️‍♂️</h1>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="table-container">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>SQL Query</th>
                                <th>Locations</th>
                                <th>Timestamp</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>{{ $warning->id }}</td>
                                    <td>{{ $warning->sql }}</td>
                                    <td>{{ $warning->location }}</td>
                                    <td>{{ $warning->created_at }}</td>
                                    <td>
                                        <form action="{{ route('n-plus-one.dashboard.destroy', $warning->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination links -->
                {{ $warnings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Include Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>