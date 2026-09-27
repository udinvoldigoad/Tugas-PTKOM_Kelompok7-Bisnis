@props(['active' => 'profil'])

<aside x-data="appSidebar" class="profile-sidebar" aria-label="Navigasi utama">
    <a class="profile-brand" href="{{ route('dashboard') }}" aria-label="Kafe Ridho">
        <img src="{{ asset('depan/logo.png') }}" alt="" width="44" height="44">
        <span class="sidebar-label">COFFE<br>RIDHO</span>
    </a>
    <button type="button" class="sidebar-toggle" @click="toggleSidebar()"
        :aria-expanded="sidebarExpanded.toString()" aria-controls="profile-navigation"
        :aria-label="sidebarExpanded ? 'Tutup sidebar' : 'Buka sidebar'">
        <span x-text="sidebarExpanded ? String.fromCharCode(8249) : String.fromCharCode(8250)" aria-hidden="true">&rsaquo;</span>
    </button>
    <nav id="profile-navigation" class="profile-navigation">
<button type="button" class="sidebar-item" disabled aria-label="Dashboard, belum tersedia" title="Dashboard belum tersedia"><span class="sidebar-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="3" width="8" height="8" rx="2" />
            <rect x="13" y="3" width="8" height="8" rx="2" />
            <rect x="3" y="13" width="8" height="8" rx="2" />
            <rect x="13" y="13" width="8" height="8" rx="2" />
          </svg></span><span class="sidebar-label">Dashboard</span></button>
<a href="{{ url('/kelola-menu') }}" class="sidebar-item {{ $active === 'kasir' ? 'is-active' : '' }}" aria-label="Kasir" title="Kasir" @if($active === 'kasir') aria-current="page" @endif><span class="sidebar-icon"><svg width="26" height="26" viewBox="0 0 32 32" fill="currentColor">
          <rect x="11" y="6" width="10" height="6" rx="1" />
          <path d="M7 14 C7 13, 8 12, 9 12 L23 12 C24 12, 25 13, 25 14 L26 21 L6 21 Z" />
          <circle cx="10" cy="15" r="0.8" fill="white"/>
          <circle cx="13" cy="15" r="0.8" fill="white"/>
          <circle cx="16" cy="15" r="0.8" fill="white"/>
          <circle cx="10" cy="18" r="0.8" fill="white"/>
          <circle cx="13" cy="18" r="0.8" fill="white"/>
          <circle cx="16" cy="18" r="0.8" fill="white"/>
          <rect x="5" y="21" width="22" height="5" rx="1.5" />
        </svg></span><span class="sidebar-label">Kasir</span></a>
<button type="button" class="sidebar-item" disabled aria-label="Riwayat, belum tersedia" title="Riwayat belum tersedia"><span class="sidebar-icon"><svg width="26" height="26" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M 8 16 A 8 8 0 1 1 12 23.5" />
          <polyline points="5,11 8,16 13,13" fill="currentColor" stroke="none"/>
          <polyline points="16,11 16,16 20,16" stroke-width="3"/>
        </svg></span><span class="sidebar-label">Riwayat</span></button>
<a href="{{ route('dashboard') }}" class="sidebar-item {{ $active === 'profil' ? 'is-active' : '' }}" aria-label="Profil" title="Profil" @if($active === 'profil') aria-current="page" @endif><span class="sidebar-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <circle cx="12" cy="7.5" r="4" />
          <path d="M4 19 C4 15.5, 7.5 14, 12 14 C16.5 14, 20 15.5, 20 19 Z" />
        </svg></span><span class="sidebar-label">Profil</span></a>
    </nav>
    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
        @csrf
        <button type="submit" class="sidebar-item" title="Logout" aria-label="Logout">
<span class="sidebar-icon"><svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg></span><span class="sidebar-label">Logout</span>
        </button>
    </form>
</aside>