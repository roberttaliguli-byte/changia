@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page_title', 'Profaili Yangu')
@section('page_subtitle', 'Badilisha taarifa zako binafsi')

@push('admin-styles')
<style>
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .avatar-large {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #1E3A5F, #2B5A8C);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
        color: white;
        margin: 0 auto 20px;
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-card text-center">
                <div class="avatar-large">
                    {{ auth()->user()->initial }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->role_display }}</p>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-envelope me-2"></i> {{ $user->email ?? 'Hakuna barua pepe' }}</p>
                    <p><i class="fas fa-phone me-2"></i> {{ $user->phone }}</p>
                    <p><i class="fas fa-calendar me-2"></i> Alijiunga: {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="profile-card">
                <h5 class="mb-4"><i class="fas fa-edit me-2"></i> Hariri Profaili</h5>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Jina Kamili</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Namba ya Simu</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Badilisha Password (Hiari)</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Password ya Sasa</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password Mpya</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Thibitisha Password Mpya</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Hifadhi Mabadiliko</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection