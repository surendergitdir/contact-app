@extends('layouts.main')

@section('content')
    <h4>Add New Contact</h4>
    <form method="POST" action="{{ route('contacts.store') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input name="phone" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
