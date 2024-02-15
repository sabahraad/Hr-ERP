<!-- resources/views/upload-form.blade.php -->

<form action="/upload-employees" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".xls, .xlsx">
    <button type="submit">Upload</button>
</form>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
