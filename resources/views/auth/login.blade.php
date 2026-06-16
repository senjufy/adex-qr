@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="margin-bottom: 0.5rem;">Admin Access</h1>
                <p class="muted">Enter the global password to continue</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-2">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required autofocus
                           style="width: 100%; box-sizing: border-box; padding: .8rem; border: 1px solid #d1d5db; border-radius: .35rem;">
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" style="width: 100%; padding: .8rem; font-size: 1rem;">Unlock Dashboard</button>
            </form>
        </div>
    </div>
@endsection
