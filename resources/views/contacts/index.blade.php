@extends('layouts.main')

@section('content')
    <a href="{{ route('contacts.create') }}" class="btn btn-primary mb-3">Add New Contact</a>

    <form action="{{ route('contacts.importXml') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="file" name="xml_file" class="form-control" required accept=".xml">
            <button class="btn btn-secondary" type="submit">Import XML</button>
        </div>
    </form>

    @if ($contacts->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this contact?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination links -->
        <div class="mt-4" style='float: right;'>
            {{ $contacts->links('pagination::bootstrap-4') }}
        </div>
    @else
        <p>No contacts found.</p>
    @endif
@endsection