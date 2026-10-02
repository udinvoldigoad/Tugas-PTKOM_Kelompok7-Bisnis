@props(['user' => null])

@php($summaryUser = $user ?? auth()->user())

<a href="{{ route('profile.edit') }}"
    {{ $attributes->class('flex min-w-[194px] items-center gap-3 rounded-full bg-[#EAE8E2] px-3 py-1.5 transition hover:bg-[#DDDAD6] focus:outline-none focus:ring-2 focus:ring-[#9B7B3F] focus:ring-offset-2') }}>
    <span class="grid h-9 w-9 shrink-0 place-items-center overflow-hidden rounded-full bg-[#5D4E43] text-xs font-bold text-white">
        @if ($summaryUser?->avatar)
            <img src="{{ asset('storage/'.$summaryUser->avatar) }}" alt="{{ $summaryUser->name }}" class="h-full w-full object-cover">
        @else
            {{ str($summaryUser?->name ?? 'K')->substr(0, 1)->upper() }}
        @endif
    </span>
    <span class="min-w-0 font-mono leading-tight">
        <strong class="block truncate text-sm text-[#1E1B18]">{{ $summaryUser?->name ?? 'Kasir' }}</strong>
        <small class="block truncate text-[10px] text-[#524F49]">{{ ucfirst($summaryUser?->role ?? 'Kasir') }} Shift {{ $summaryUser?->shift ?? '1' }}</small>
    </span>
</a>
