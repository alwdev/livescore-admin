@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" class="border rounded w-full p-2" value="{{ old('name', $user->name) }}">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" class="border rounded w-full p-2"
                    value="{{ old('email', $user->email) }}">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">New Password (optional)</label>
                <input type="password" name="password" class="border rounded w-full p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Role</label>
                <select name="role" class="border rounded w-full p-2">
                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Status</label>
                <select name="status" class="border rounded w-full p-2">
                    <option value="1" {{ $user->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$user->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update User</button>
        </form>
    </div>
@endsection
