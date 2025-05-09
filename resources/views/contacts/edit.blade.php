@extends('layouts.main')

@section('content')
    <h4>Edit Contact</h4>
    <form method="POST" action="{{ route('contacts.update', $contact) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input name="name" value="{{ $contact->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input name="phone" value="{{ $contact->phone }}" class="form-control">
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
