@extends('layouts.app')

@section('title', 'Hariri Tukio')
@section('page_title', 'Hariri Tukio')

@section('content')
<div class="container-fluid px-0 px-md-2">
    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('events.update', $event) }}" id="editEventForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Jina la Tukio <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="event_name" 
                                   class="form-control form-control-sm" 
                                   value="{{ old('event_name', $event->event_name) }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Aina ya Tukio <span class="text-danger">*</span></label>
                            <select name="event_type" class="form-select form-select-sm" required>
                                <option value="harusi" {{ old('event_type', $event->event_type) == 'harusi' ? 'selected' : '' }}>Harusi</option>
                                <option value="sendoff" {{ old('event_type', $event->event_type) == 'sendoff' ? 'selected' : '' }}>Send-off</option>
                                <option value="birthday" {{ old('event_type', $event->event_type) == 'birthday' ? 'selected' : '' }}>Siku ya Kuzaliwa</option>
                                <option value="graduation" {{ old('event_type', $event->event_type) == 'graduation' ? 'selected' : '' }}>Graduation</option>
                                <option value="kitchen" {{ old('event_type', $event->event_type) == 'kitchen' ? 'selected' : '' }}>Kitchen Party</option>
                                <option value="baby" {{ old('event_type', $event->event_type) == 'baby' ? 'selected' : '' }}>Baby Shower</option>
                                <option value="fundraising" {{ old('event_type', $event->event_type) == 'fundraising' ? 'selected' : '' }}>Harambee</option>
                                <option value="other" {{ old('event_type', $event->event_type) == 'other' ? 'selected' : '' }}>Nyingine</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tarehe ya Tukio <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="event_date" 
                                   class="form-control form-control-sm" 
                                   value="{{ old('event_date', $event->event_date instanceof \Carbon\Carbon ? $event->event_date->format('Y-m-d') : date('Y-m-d', strtotime($event->event_date))) }}"
                                   required>
                            <small class="text-secondary">Tarehe lazima iwe leo au baadaye</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kiasi Lengwa (TSh)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">TSh</span>
                                <input type="number" 
                                       name="target_amount" 
                                       class="form-control" 
                                       value="{{ old('target_amount', $event->target_amount) }}"
                                       min="0"
                                       step="1000">
                            </div>
                            <small class="text-secondary">Hiari - Acha ikiwa huna lengo maalum</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Hali ya Tukio</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ old('status', $event->status) == 'active' ? 'selected' : '' }}>Inaendelea</option>
                                <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Imekamilika</option>
                                <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Imefutwa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Maelezo ya Tukio</label>
                    <textarea name="description" 
                              class="form-control form-control-sm" 
                              rows="3">{{ old('description', $event->description) }}</textarea>
                    <small class="text-secondary">Maelezo yataonekana kwa wachangiaji (hiari)</small>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Ghairi
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm" style="background: var(--primary);">
                        <i class="fas fa-save me-1"></i>Hifadhi Mabadiliko
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone -->
    @if($event->contributions()->count() == 0)
    <div class="card border-danger shadow-sm mt-4">
        <div class="card-header bg-danger text-white p-2">
            <small class="fw-bold">Eneo la Hatari</small>
        </div>
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="fw-semibold">Futa Tukio</small>
                    <p class="small text-secondary mb-0">Tukio likifutwa, haiwezi kurejeshwa. Hakuna michango iliyorekodiwa kwenye tukio hili.</p>
                </div>
                <form method="POST" action="{{ route('events.destroy', $event) }}" id="deleteEventForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                        <i class="fas fa-trash me-1"></i>Futa Tukio
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="card border-warning shadow-sm mt-4">
        <div class="card-header bg-warning text-dark p-2">
            <small class="fw-bold">Kumbuka</small>
        </div>
        <div class="card-body p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-info-circle text-warning fa-2x"></i>
                <div>
                    <small class="fw-semibold">Huwezi Kufuta Tukio Hili</small>
                    <p class="small text-secondary mb-0">Tukio lina michango {{ $event->contributions()->count() }} iliyorekodiwa. Futa michango yote kwanza kabla ya kufuta tukio.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Add client-side validation for date
    document.getElementById('editEventForm')?.addEventListener('submit', function(e) {
        const dateInput = document.querySelector('input[name="event_date"]');
        if (dateInput) {
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tarehe isiyo sahihi',
                    text: 'Tarehe ya tukio lazima iwe leo au baadaye',
                    confirmButtonColor: '#FF6F00'
                });
            }
        }
    });
    
    function confirmDelete() {
        Swal.fire({
            title: 'Una uhakika?',
            text: "Tukio litafutwa kabisa na haliwezi kurejeshwa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ndio, Futa!',
            cancelButtonText: 'Ghairi'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteEventForm').submit();
            }
        });
    }
</script>
@endpush