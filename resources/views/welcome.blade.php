<!DOCTYPE html>
<html>
<head>
    <title>Import CSV</title>@vite('resources/css/app.css')
</head>
<body>
<div class="container  mt-5 mb-5">
<h1 class="title">Upload file</h1>
@if ($errors->any())
    <div class="notification is-danger is-light">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if( request()->get('success') )
        <div class="notification is-light">
            <ul>
                <li>{{ request()->get('success') }}</li>
            </ul>
        </div>
@endif


<form action="{{ route('persons.upload') }}" method="POST" enctype="multipart/form-data" class="form-upload">
    @csrf
    <div class="form-group d-flex align-items-center">
        <label for="file_upload">Import Customers</label>
        <input type="file" name="file_upload" id="file_upload" class="form-control-file ms-auto"> </div>
    </div>
    <button type="submit" class="btn btn-primary">Import</button>
</form>

<form action="{{ route('persons.delete') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-warning">Delete All</button>
</form>

<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Title</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($persons as $person)
        <tr>
            <td>{{ $person->title }}</td>
            <td>{{ $person->firstname ?? $person->initial }}</td>
            <td>{{ $person->lastname }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center">No customers found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>
<style>
    /* Add these styles to your main CSS file */
    .table {
        width: 100%; /* Adjust width as needed */
        border-collapse: collapse;
        padding: 10px;
    }
    .table-striped tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.05); /* Light background for odd rows */
    }
    th,
    td {
        padding: 1rem;
        border: 1px solid #ddd;
        text-align: left; /* Adjust alignment if needed */
    }
    .form-upload {
        display: flex; /* Arrange elements horizontally */
        flex-direction: column; /* Stack elements vertically */
        gap: 1rem; /* Add space between elements */

    }
    .form-group {
        display: flow;
        align-items: center; /* Align label and input vertically */
        align-content: baseline;
        padding: 10px;
    }
    .form-group label {
        flex: 1; /* Allow label to fill available space */
    }
    .form-control-file {
        padding: 0.5rem 1rem; /* Adjust padding for input */
        border: 2px solid #ccc; /* Add border */
        border-radius: 3rem; /* Rounded corners */
    }
    .btn-primary {
        background-color: #007bff; /* Blue button color */
        color: #fff; /* White text color */
        border: none; /* Remove border */
        border-radius: 0.25rem; /* Rounded corners */
        padding: 0.5rem 1rem; /* Adjust padding for button */
        width: 20%;
        margin-bottom: 2rem;
    }

    .btn-warning {
        background-color: red; /* Red button color */
        color: #fff; /* White text color */
        border: none; /* Remove border */
        border-radius: 0.25rem; /* Rounded corners */
        padding: 0.5rem 1rem; /* Adjust padding for button */
        width: 20%;
        margin-bottom: 2rem;
    }
</style>
</body>
</html>
