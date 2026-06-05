{{-- resources/views/admin/reportevents.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ripoti ya Matukio')
@section('page_title', 'Ripoti ya Matukio')
@section('page_subtitle', 'Takwimu za matukio yote kwenye mfumo')

@push('admin-styles')
<style>
    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid var(--admin-primary);
    }
    .stat-box h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .table-events {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    .table-events th {
        background: #F8FAFC;
        padding: 12px 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .table-events td {
        padding: 12px 15px;
        font-size: 0.8rem;
        vertical-align: middle;
    }
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    .badge-active { background: #D1FAE5; color: #059669; }
    .badge-completed { background: #DBEAFE; color: #2563EB; }
    .badge-cancelled { background: #FEE2E2; color: #DC2626; }
</style>
@endpush

@section('admin-content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-box">
                <small class="text-muted">JUMLA YA MATUKIO</small>
                <h3>{{ number_format($totalEvents) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <small class="text-muted">JUMLA YA MICHANGO ILIYOKUSANYWA</small>
                <h3>{{ number_format($totalCollected) }} <small class="fs-6">TSh</small></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <small class="text-muted">JUMLA ILIYOAHDIWA</small>
                <h3>{{ number_format($totalPromised) }} <small class="fs-6">TSh</small></h3>
            </div>
        </div>
    </div>

    <!-- Event Status Distribution -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stat-box">
                <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i> Usambazaji wa Matukio kwa Hali</h6>
                <div class="row">
                    @foreach($eventStats as $stat)
                    <div class="col-4 text-center">
                        <div class="badge-status badge-{{ $stat->status }} d-inline-block mb-2">
                            {{ $stat->status == 'active' ? 'Inaendelea' : ($stat->status == 'completed' ? 'Imekamilika' : 'Imefutwa') }}
                        </div>
                        <h4 class="mb-0">{{ $stat->count }}</h4>
                        <small class="text-muted">Matukio</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-box">
                <h6 class="mb-3"><i class="fas fa-chart-line me-2"></i> Takwimu za Kila Mwezi</h6>
                <canvas id="monthlyEventsChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="table-events">
        <div class="p-3 border-bottom bg-white">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i> Orodha ya Matukio Yote</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jina la Tukio</th>
                        <th>Mratibu</th>
                        <th>Tarehe</th>
                        <th>Wachangiaji</th>
                        <th>Iliyokusanywa</th>
                        <th>Iliyoahidiwa</th>
                        <th>Hali</th>
                        <th>Kitendo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                    <tr>
                        <td>{{ $events->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ \Str::limit($event->event_name, 30) }}</td>
                        <td>{{ $event->user->name ?? 'N/A' }}</td>
                        <td>{{ $event->event_date->format('d/m/Y') }}</td>
                        <td>{{ $event->contributors_count ?? 0 }}</td>
                        <td class="text-success fw-semibold">{{ number_format($event->total_collected ?? 0) }} TSh</td>
                        <td class="text-warning fw-semibold">{{ number_format($event->contributors->sum('promised_amount') ?? 0) }} TSh</td>
                        <td>
                            <span class="badge-status badge-{{ $event->status }}">
                                {{ $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.events.view', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-2x mb-2 d-block text-muted"></i>
                            Hakuna matukio yaliyopatikana
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-white">
            {{ $events->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Events Chart
    const monthlyData = @json($monthlyEvents);
    const ctx = document.getElementById('monthlyEventsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Matukio Yaliyoundwa',
                data: monthlyData.map(item => item.count),
                borderColor: '#1E3A5F',
                backgroundColor: 'rgba(30, 58, 95, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>
@endsection