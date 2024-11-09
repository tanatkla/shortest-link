@extends('layouts.guest')
@section('content')
<div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        @if (session('status'))
            <div class="alert alert-success mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif
        {{-- <div class="mb-3 text-center"> --}}
            <a href="{{ route('short-link-guest-forms.index') }}" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-primary text-center">{{ __('เข้าสู่ระบบ') }}</h2>
        {{-- </div> --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('อีเมลหรือชื่อผู้ใช้งาน') }}<span class="text-danger">*</span></label>
                <input id="email" type="text" name="email" class="form-control" value="{{ old('email') }}" autofocus>
                @if ($errors->has('email'))
                    <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('รหัสผ่าน') }}<span class="text-danger">*</span></label>
                <input id="password" type="password" name="password" class="form-control" autocomplete="current-password">
                @if ($errors->has('password'))
                    <div class="text-danger mt-1">{{ $errors->first('password') }}</div>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a class="text-muted text-decoration-none" href="{{ route('register') }}">
                    {{ __('ลงทะเบียน') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('เข้าสู่ระบบ') }}
                </button>
            </div>
        </form>
    </div>
@endsection
