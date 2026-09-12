<!-- Dropdown Menu (Shared) -->
<div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all">
    <div class="px-4 py-2 border-b border-slate-100">
        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->display_name }}</p>
        <p class="text-[10px] uppercase tracking-wider text-purple-700 font-semibold truncate">
            {{ auth()->user()->role === 'Advisor' ? (auth()->user()->company->name ?? 'Company Supervisor') : auth()->user()->display_role }}
        </p>
    </div>
    <a href="{{ route('account.profile') }}" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-purple-50 hover:text-purple-900 flex items-center gap-2.5 font-semibold transition-colors">
        <span class="material-symbols-outlined text-[18px] text-purple-600">person</span>
        <span>Account Profile</span>
    </a>
    <a href="{{ route('account.profile') }}#security" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-purple-50 hover:text-purple-900 flex items-center gap-2.5 font-semibold transition-colors">
        <span class="material-symbols-outlined text-[18px] text-purple-600">lock_reset</span>
        <span>Change Password</span>
    </a>
    <div class="border-t border-slate-100 my-1"></div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 font-semibold transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">logout</span>
            <span>Logout</span>
        </button>
    </form>
</div>
