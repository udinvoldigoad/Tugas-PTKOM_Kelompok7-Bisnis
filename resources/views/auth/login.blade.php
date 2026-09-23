<x-guest-layout>
    {{-- Session Status --}}
    @if (session('status'))
        <div class="session-status" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2>Login</h2>

        {{-- Username / Email --}}
        <div class="field-group">
            <label class="field-label" for="email">Username</label>
            <div class="field-input-wrap">
                <input
                    id="email"
                    class="field-input"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan Username"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="field-group">
            <label class="field-label" for="password">Password</label>
            <div class="field-input-wrap">
                <input
                    id="password"
                    class="field-input"
                    type="password"
                    name="password"
                    placeholder="Masukkan Password"
                    required
                    autocomplete="current-password"
                >
                {{-- Eye toggle button --}}
                <button
                    type="button"
                    id="toggle-pwd"
                    class="toggle-password"
                    aria-label="Tampilkan password"
                >
                    {{-- Eye icon (default: show — artinya password sedang tersembunyi) --}}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Login Button --}}
        <button type="submit" id="btn-submit-login" class="btn-login">
            Login
        </button>

    </form>
</x-guest-layout>
