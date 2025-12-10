@php
    $user = filament()->auth()->user();
    $items = filament()->getUserMenuItems();

    $profileItem = $items['profile'] ?? $items['account'] ?? null;
    $profileItemUrl = $profileItem?->getUrl();
    $profilePage = filament()->getProfilePage();
    $hasProfileItem = filament()->hasProfile() || filled($profileItemUrl);

    $logoutItem = $items['logout'] ?? null;

    $items = \Illuminate\Support\Arr::except($items, ['account', 'logout', 'profile']);
@endphp

{{-- WRAPPER FLEX UTAMA: Agar Lonceng & Profil Sejajar Rapi --}}
<div
        class="flex items-center gap-x-4"
        x-data="{
            collapsed: localStorage.getItem('profileCollapsed') === 'true',
            toggle() {
                this.collapsed = !this.collapsed;
                localStorage.setItem('profileCollapsed', this.collapsed);
            }
        }"
    >
        {{-- 1. HOOK NOTIFIKASI (Dikeluarkan dari Dropdown agar tidak tumpang tindih) --}}
        <div class="shrink-0 flex items-center">
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_BEFORE) }}
        </div>

        {{-- 2. USER MENU DROPDOWN --}}

    <x-filament::dropdown placement="bottom-end" teleport>
        <x-slot name="trigger">
            <div class="relative flex items-center gap-x-2">
                {{-- Main Profile Button - COLLAPSIBLE --}}
                <button
                    aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
                    type="button"
                    id="profileTrigger"
                    class="group relative flex items-center shrink-0 rounded-xl px-3 py-2 transition-all duration-300 ease-out hover:bg-gray-100 dark:hover:bg-gray-800/50 hover:shadow-md hover:scale-[1.02] active:scale-[0.98]"
                    style="gap: 0.75rem;"
                >
                    {{-- Animated Background Gradient on Hover --}}
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-500/0 via-primary-500/0 to-primary-600/0 opacity-0 transition-opacity duration-300 group-hover:opacity-10"></div>

                    {{-- Avatar Container with Ring Animation --}}
                    <div class="relative flex-shrink-0">
                        {{-- Pulsing Ring Effect --}}
                        <div class="absolute inset-0 rounded-full bg-primary-500/20 animate-ping opacity-0 group-hover:opacity-75"></div>

                        {{-- Rotating Border Ring --}}
                        <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-primary-500 via-purple-500 to-pink-500 opacity-0 blur-sm transition-opacity duration-500 group-hover:opacity-60 group-hover:animate-spin-slow"></div>

                        {{-- Avatar with Scale Animation --}}
                        <div class="relative transition-transform duration-300 group-hover:scale-110">
                            <x-filament-panels::avatar.user
                                :user="$user"
                                class="ring-2 ring-white dark:ring-gray-900 transition-all duration-300 group-hover:ring-primary-500/50"
                            />
                        </div>
                    </div>

                    {{-- User Info - COLLAPSIBLE CONTENT --}}
                    <div id="profileInfo" class="hidden lg:flex flex-col text-left min-w-0 relative overflow-hidden transition-all duration-300 opacity-100" style="max-width: 150px;">
                        {{-- Decorative Line that Expands --}}
                        <div class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-primary-500 to-purple-500 transition-all duration-300 group-hover:w-full"></div>

                        {{-- Nama Lengkap --}}
                        <span class="text-sm font-bold leading-none truncate transition-all duration-300
                            text-gray-950 dark:text-gray-400
                            group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r
                            group-hover:from-primary-600 group-hover:via-purple-600 group-hover:to-primary-600
                            dark:group-hover:from-primary-400 dark:group-hover:via-purple-400 dark:group-hover:to-primary-400">
                            {{ $user->getFilamentName() }}
                        </span>

                        {{-- Unit Kerja with Icon --}}
                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 text-gray-400 transition-all duration-300 group-hover:text-primary-500 group-hover:scale-110 group-hover:-translate-y-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>

                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-tight transition-all duration-300 group-hover:text-gray-700 dark:group-hover:text-gray-300 truncate">
                                {{ $user->units->first()?->nama_unit ?? 'Tanpa Unit' }}
                            </span>
                        </div>
                    </div>

                    {{-- Chevron Icon - COLLAPSIBLE --}}
                    <svg id="chevronIcon" class="hidden lg:block w-4 h-4 text-gray-400 transition-all duration-300 group-hover:text-primary-500 group-hover:translate-x-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>

                    {{-- Tooltip - Show on Mobile & Collapsed Desktop --}}
                    <span id="profileTooltip" class="lg:hidden absolute -top-12 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 pointer-events-none transition-opacity duration-200 group-hover:opacity-100 whitespace-nowrap z-50">
                        {{ $user->getFilamentName() }}
                        <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></span>
                    </span>
                </button>

                {{-- Toggle Collapse Button --}}
                <button
                    type="button"
                    onclick="toggleProfileCollapse(event)"
                    id="toggleCollapseBtn"
                    class="hidden lg:flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-primary-500 dark:hover:bg-primary-500 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110 active:scale-95 flex-shrink-0"
                    aria-label="Toggle profile view"
                >
                    <svg id="collapseIcon" class="w-4 h-4 text-gray-600 dark:text-gray-300 hover:text-white transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            {{-- JavaScript untuk Toggle Collapse --}}
            <script>
                function toggleProfileCollapse(event) {
                    // Prevent menu from opening
                    event.stopPropagation();

                    const profileInfo = document.getElementById('profileInfo');
                    const chevronIcon = document.getElementById('chevronIcon');
                    const collapseIcon = document.getElementById('collapseIcon');
                    const profileTrigger = document.getElementById('profileTrigger');
                    const profileTooltip = document.getElementById('profileTooltip');

                    // Check current state
                    const isCollapsed = profileInfo.classList.contains('!hidden');

                    if (isCollapsed) {
                        // EXPAND - Show nama & unit
                        profileInfo.classList.remove('!hidden', '!w-0', '!opacity-0');
                        profileInfo.classList.add('!flex', '!opacity-100');
                        profileInfo.style.maxWidth = '150px';

                        chevronIcon.classList.remove('!hidden');

                        // Rotate arrow to left
                        collapseIcon.style.transform = 'rotate(0deg)';

                        // Hide tooltip on desktop when expanded
                        profileTooltip.classList.add('lg:!hidden');

                        // Adjust button gap
                        profileTrigger.style.gap = '0.75rem';

                        // Save state
                        localStorage.setItem('profileCollapsed', 'false');
                    } else {
                        // COLLAPSE - Hide nama & unit
                        profileInfo.classList.add('!hidden', '!w-0', '!opacity-0');
                        profileInfo.classList.remove('!flex', '!opacity-100');
                        profileInfo.style.maxWidth = '0';

                        chevronIcon.classList.add('!hidden');

                        // Rotate arrow to right
                        collapseIcon.style.transform = 'rotate(180deg)';

                        // Show tooltip on desktop when collapsed
                        profileTooltip.classList.remove('lg:!hidden');

                        // Adjust button gap
                        profileTrigger.style.gap = '0';

                        // Save state
                        localStorage.setItem('profileCollapsed', 'true');
                    }
                }

                // Restore saved state on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const isCollapsed = localStorage.getItem('profileCollapsed') === 'true';

                    if (isCollapsed) {
                        const profileInfo = document.getElementById('profileInfo');
                        const chevronIcon = document.getElementById('chevronIcon');
                        const collapseIcon = document.getElementById('collapseIcon');
                        const profileTrigger = document.getElementById('profileTrigger');
                        const profileTooltip = document.getElementById('profileTooltip');

                        // Apply collapsed state immediately
                        profileInfo.classList.add('!hidden', '!w-0', '!opacity-0');
                        profileInfo.classList.remove('!flex');
                        profileInfo.style.maxWidth = '0';

                        chevronIcon.classList.add('!hidden');
                        collapseIcon.style.transform = 'rotate(180deg)';

                        profileTooltip.classList.remove('lg:!hidden');
                        profileTrigger.style.gap = '0';
                    }
                });
            </script>

            {{-- Custom CSS --}}
            <style>
                @keyframes spin-slow {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }

                .animate-spin-slow {
                    animation: spin-slow 3s linear infinite;
                }

                /* Smooth transition for collapsible content */
                #profileInfo {
                    transition: max-width 0.3s ease-out, opacity 0.3s ease-out !important;
                }

                /* Ensure proper stacking */
                #toggleCollapseBtn {
                    z-index: 10;
                }

                /* Improve gradient text visibility in dark mode */
                @media (prefers-color-scheme: dark) {
                    .group:hover [class*="text-transparent"] {
                        filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.3));
                    }
                }
            </style>
        </x-slot>

        @if ($profileItem?->isVisible() ?? true)
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}

            @if ($hasProfileItem)
                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item
                        :color="$profileItem?->getColor()"
                        :icon="$profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.profile-item') ?? 'heroicon-m-user-circle'"
                        :href="$profileItemUrl ?? filament()->getProfileUrl()"
                        :target="($profileItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null"
                        tag="a"
                    >
                        {{ $profileItem?->getLabel() ?? ($profilePage ? $profilePage::getLabel() : null) ?? filament()->getUserName($user) }}
                    </x-filament::dropdown.list.item>
                </x-filament::dropdown.list>
            @else
                <x-filament::dropdown.header
                    :color="$profileItem?->getColor()"
                    :icon="$profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.profile-item') ?? 'heroicon-m-user-circle'"
                >
                    {{ $profileItem?->getLabel() ?? filament()->getUserName($user) }}
                </x-filament::dropdown.header>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
        @endif

        @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
            <x-filament::dropdown.list>
                <x-filament-panels::theme-switcher />
            </x-filament::dropdown.list>
        @endif

        <x-filament::dropdown.list>
            @foreach ($items as $key => $item)
                @php
                    $itemPostAction = $item->getPostAction();
                @endphp

                <x-filament::dropdown.list.item
                    :action="$itemPostAction"
                    :color="$item->getColor()"
                    :href="$item->getUrl()"
                    :icon="$item->getIcon()"
                    :method="filled($itemPostAction) ? 'post' : null"
                    :tag="filled($itemPostAction) ? 'form' : 'a'"
                    :target="$item->shouldOpenUrlInNewTab() ? '_blank' : null"
                >
                    {{ $item->getLabel() }}
                </x-filament::dropdown.list.item>
            @endforeach

            <x-filament::dropdown.list.item
                :action="$logoutItem?->getUrl() ?? filament()->getLogoutUrl()"
                :color="$logoutItem?->getColor()"
                :icon="$logoutItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.logout-button') ?? 'heroicon-m-arrow-left-on-rectangle'"
                method="post"
                tag="form"
            >
                {{ $logoutItem?->getLabel() ?? __('filament-panels::layout.actions.logout.label') }}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
