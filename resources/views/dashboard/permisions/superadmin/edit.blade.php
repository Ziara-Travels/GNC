@extends('layouts.admin')

@section('title', 'Manage Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="material-symbols-outlined me-2">security</i>
                        Permissions Management
                    </h4>
                    <a href="{{ route('superadmin.permissions.create') }}" class="btn btn-primary">
                        <i class="material-symbols-outlined me-1">add</i>
                        Add New Permission
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter by Group -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="groupFilter">
                                <option value="">All Groups</option>
                                @foreach($permissions