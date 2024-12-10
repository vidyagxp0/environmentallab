@php
    $mainmenu = 'Activity';
    $submenu = 'Login Logs';
@endphp

@extends('admin.layout')

@section('container')
<div class="container mt-4">
    <h2>Login Logs</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Account</th>
                <th>IP Address</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->account }}</td>
                    <td>{{ $log->ip }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No logs found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
