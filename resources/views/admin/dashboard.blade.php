@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Muhtasari wa mfumo')

@push('admin-styles')
<style>
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 6px 10px;
        margin-bottom: 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    
    .stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
    
    .bg-primary-gradient {
        background: linear-gradient(135deg, #FF6F00, #FF9800);
    }
    
    .bg-success-gradient {
        background: linear-gradient(135deg, #10B981, #059669);
    }
    
    .recent-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .recent-table th {
        background: #F8FAFC;
        padding: 12px 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
    }
    
    .recent-table td {
        padding: 12px 15px;
        font-size: 0.8rem;
        border-bottom: 1px solid #E2E8F0;
    }
    
    .badge-role {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-role-admin { background: #FEE2E2; color: #DC2626; }
    .badge-role-accountant { background: #DBEAFE; color: #2563EB; }
    .badge-role-event_user { background: #D1FAE5; color: #059669; }
    
    .btn-sm-custom {
        padding: 6px 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Section header */
    .section-header {
        padding: 12px 15px;
        background: white;
        border-bottom: 1px solid #E2E8F0;
    }
    
    .section-header h5 {
        font-size: 0.85rem;
        font-weight: 700;
        margin: 0;
    }
    
    /* Mobile optimizations - keeping recent tables spacious */
    @media (max-width: 768px) {
        .stat-card {
            padding: 4px 8px;
        }
        
        .stat-icon {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
        
        .stat-card h2 {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .stat-card p.mb-1 {
            font-size: 0.5rem;
            margin-bottom: 2px !important;
        }
        
        .recent-table th,
        .recent-table td {
            padding: 10px 12px;
            font-size: 0.75rem;
        }
        
        .recent-table th {
            font-size: 0.65rem;
        }
        
        .section-header {
            padding: 10px 12px;
        }
        
        .section-header h5 {
            font-size: 0.75rem;
        }
        
        .badge-role {
            padding: 3px 8px;
            font-size: 0.65rem;
        }
        
        .btn-sm-custom {
            padding: 5px 12px;
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 576px) {
        .stat-card h2 {
            font-size: 0.85rem;
        }
        
        .stat-icon {
            width: 24px;
            height: 24px;
            font-size: 0.7rem;
        }
        
        .recent-table th,
        .recent-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }
        
        .recent-table th {
            font-size: 0.6rem;
        }
        
        .badge-role {
            font-size: 0.6rem;
            padding: 2px 6px;
        }
        
        .btn-sm-custom {
            padding: 4px 10px;
            font-size: 0.65rem;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-2 px-md-3">
    <!-- Stats - Only Users and Events - Compact -->
    <div class="row g-2 mb-4">
        <div class="col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Watumiaji</p>
                        <h2 class="mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <div class="stat-icon bg-primary-gradient text-white">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Matukio</p>
                        <h2 class="mb-0">{{ number_format($totalEvents) }}</h2>
                    </div>
                    <div class="stat-icon bg-success-gradient text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="recent-table">
                <div class="section-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2" style="color: #FF6F00;"></i> 
                        Watumiaji Wapya
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Jina</th>
                                <th>Jukumu</th>
                                <th>Tarehe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>
                                    <span class="badge-role badge-role-{{ $user->role }}">
                                        {{ $user->role_display }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4">Hakuna watumiaji</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top bg-white text-end">
                    <a href="{{ route('admin.users') }}" class="btn btn-sm-custom btn-outline-primary">
                        Tazama Wote <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Events -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="recent-table">
                <div class="section-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2" style="color: #FF6F00;"></i> 
                        Matukio ya Hivi Karibuni
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Jina la Tukio</th>
                                <th>Tarehe</th>
                                <th>Hali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents as $event)
                            <tr>
                                <td class="fw-semibold">{{ \Str::limit($event->event_name, 30) }}</td>
                                <td>{{ $event->event_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $event->status == 'active' ? 'success' : ($event->status == 'completed' ? 'info' : 'secondary') }}" style="font-size: 0.7rem; padding: 4px 8px;">
                                        {{ $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4">Hakuna matukio</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top bg-white text-end">
                    <a href="{{ route('admin.events') }}" class="btn btn-sm-custom btn-outline-primary">
                        Tazama Wote <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection