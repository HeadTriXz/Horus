<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-100">
        <a href="/" class="flex items-center pl-2.5 mb-5">
            <img src="/images/logo.svg" class="h-6 mr-3 sm:h-7" alt="Horus Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap">Horus</span>
        </a>
        <ul class="space-y-2 font-medium">
            @content()

            <li>
                @component('nav-link.php', [ 'route' => 'profile', 'name' => 'Profile' ])
                    <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="6" r="4" fill="currentColor"/>
                        <path d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" fill="currentColor"/>
                    </svg>
                @endcomponent
            </li>
        </ul>
        <div class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200">
            @component('nav-link.php', [ 'route' => 'logout', 'name' => 'Logout' ])
                <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 9.56757V14.4324C3.5 16.7258 3.5 17.8724 4.22161 18.5849C4.87719 19.2321 5.89578 19.2913 7.81846 19.2968C7.71686 18.6224 7.69563 17.8168 7.69029 16.8689C7.68802 16.4659 8.01709 16.1374 8.42529 16.1351C8.83348 16.1329 9.16624 16.4578 9.16851 16.8608C9.17451 17.9247 9.20249 18.6789 9.30898 19.2512C9.41158 19.8027 9.57634 20.1219 9.81626 20.3588C10.089 20.6281 10.4719 20.8037 11.1951 20.8996C11.9395 20.9985 12.9261 21 14.3407 21H15.3262C16.7407 21 17.7273 20.9985 18.4717 20.8996C19.1949 20.8037 19.5778 20.6281 19.8505 20.3588C20.1233 20.0895 20.3011 19.7114 20.3983 18.9975C20.4984 18.2626 20.5 17.2885 20.5 15.8919V8.10811C20.5 6.71149 20.4984 5.73743 20.3983 5.0025C20.3011 4.28855 20.1233 3.91048 19.8505 3.6412C19.5778 3.37192 19.1949 3.19635 18.4717 3.10036C17.7273 3.00155 16.7407 3 15.3262 3H14.3407C12.9261 3 11.9395 3.00155 11.1951 3.10036C10.4719 3.19635 10.089 3.37192 9.81626 3.6412C9.57634 3.87807 9.41158 4.19728 9.30898 4.74877C9.20249 5.32112 9.17451 6.07525 9.16851 7.1392C9.16624 7.54221 8.83348 7.8671 8.42529 7.86485C8.01709 7.86261 7.68802 7.53409 7.69029 7.13107C7.69563 6.18322 7.71686 5.37758 7.81846 4.70325C5.89578 4.70867 4.87719 4.76789 4.22161 5.41515C3.5 6.12759 3.5 7.27425 3.5 9.56757ZM5.93385 12.516C5.6452 12.231 5.6452 11.769 5.93385 11.484L7.90484 9.53806C8.19348 9.25308 8.66147 9.25308 8.95011 9.53806C9.23876 9.82304 9.23876 10.2851 8.95011 10.5701L8.24088 11.2703L15.3259 11.2703C15.7341 11.2703 16.0651 11.597 16.0651 12C16.0651 12.403 15.7341 12.7297 15.3259 12.7297L8.24088 12.7297L8.95011 13.4299C9.23876 13.7149 9.23876 14.177 8.95011 14.4619C8.66147 14.7469 8.19348 14.7469 7.90484 14.4619L5.93385 12.516Z" fill="currentColor"/>
                </svg>
            @endcomponent
        </div>
    </div>
</aside>
