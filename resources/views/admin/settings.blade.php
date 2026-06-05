@extends('layouts.admin')

@section('title', 'Admin Settings')
@section('page_title', 'Mipangilio')
@section('page_subtitle', 'Simamia mipangilio ya mfumo')

@push('admin-styles')
<style>
    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="settings-card">
                <h5 class="mb-4"><i class="fas fa-cog me-2"></i> Mipangilio ya Mfumo</h5>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jina la Mfumo</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sarafu</label>
                            <input type="text" class="form-control" value="{{ $settings['currency'] }}" disabled>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Maelezo ya Mfumo</label>
                            <textarea name="site_description" class="form-control" rows="3">{{ $settings['site_description'] }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Muda (Timezone)</label>
                            <select name="timezone" class="form-select">
                                <option value="Africa/Dar_es_Salaam" {{ $settings['timezone'] == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Dar es Salaam (Tanzania)</option>
                                <option value="Africa/Nairobi" {{ $settings['timezone'] == 'Africa/Nairobi' ? 'selected' : '' }}>Nairobi (Kenya)</option>
                                <option value="Africa/Kampala" {{ $settings['timezone'] == 'Africa/Kampala' ? 'selected' : '' }}>Kampala (Uganda)</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Arifa za SMS</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="enable_sms" class="form-check-input" id="enableSms" {{ $settings['enable_sms'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableSms">Wezesha utumaji wa SMS</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Arifa za Barua Pepe</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="email_notifications" class="form-check-input" id="emailNotifications" {{ $settings['email_notifications'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">Wezesha arifa za barua pepe</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <button type="submit" class="btn btn-primary">Hifadhi Mipangilio</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection