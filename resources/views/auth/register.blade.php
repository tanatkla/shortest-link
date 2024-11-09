@extends('layouts.guest')
@section('content')
<div class="card w-50 shadow-sm p-4" >
<form method="POST" action="{{ route('register') }}" class="container ">
        @csrf
        <a href="{{ route('short-link-guest-forms.index') }}" class="btn btn-link text-decoration-none">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-center text-xl text-primary mb-4">{{ __('ลงทะเบียน') }}</h2>

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('ชื่อผู้ใช้งาน') }}<span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="{{ old('name') }}" autofocus autocomplete="name">
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('อีเมล') }}<span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control"
                   value="{{ old('email') }}" autocomplete="username">
            @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('รหัสผ่าน') }}<span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
            @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('ยืนยันรหัสผ่าน') }}<span class="text-danger">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                   autocomplete="new-password">
            @error('password_confirmation')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                {{ __('เข้าสู่ระบบ') }}
            </a>
            <button type="submit" class="btn btn-primary">{{ __('ลงทะเบียน') }}</button>
        </div>
    </form>
</div>
@endsection
