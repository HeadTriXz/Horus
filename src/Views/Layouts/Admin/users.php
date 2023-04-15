@layout('app.php')
<div class="flex">
    <div class="w-1/2 p-4 sm:ml-64">
        <div class="flex items-center space-x-4 mb-4">
            <h1 class="text-2xl font-bold">Users</h1>
            @content('new-button')
        </div>
        <form action="{{ route('users') }}" class="flex items-center mb-4">
            @component('search-bar.php') @endcomponent
            <ul class="inline-flex -space-x-px ml-6">
                <li>
                    <button name="f" value="0" class="{{ $filter === 0 ? 'font-bold' : '' }} px-3 py-2.5 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                        Student
                    </button>
                </li>
                <li>
                    <button name="f" value="1" class="{{ $filter === 1 ? 'font-bold' : '' }} px-3 py-2.5 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                        Teacher
                    </button>
                </li>
                <li>
                    <button name="f" value="2" class="{{ $filter === 2 ? 'font-bold' : '' }} px-3 py-2.5 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                        Admin
                    </button>
                </li>
                <li>
                    <button class="{{ $filter === null ? 'font-bold' : '' }} px-3 py-2.5 leading-tight text-gray-500 bg-gray-100 border border-gray-300 rounded-r-lg hover:bg-gray-200 hover:text-gray-700">
                        All
                    </button>
                </li>
            </ul>
        </form>

        <ul class="space-y-4">
            @foreach($users as $user)
                <li>
                    <a href="{{ route('users', [ 'u' => $user->id ]) }}" class="flex w-full p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                        <?php
                            $bgColor = isset($selected) && $user->id === $selected->id
                                ? "bg-black"
                                : "bg-secondary";
                        ?>
                        <div class="flex my-auto h-14 w-14 rounded-xl {{ $bgColor }}">
                            <p class="text-white text-2xl font-bold m-auto">
                                <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M25.3798 25.38C27.3332 23.4293 27.3332 20.2853 27.3332 14C27.3332 7.71463 27.3332 4.57196 25.3798 2.61863C23.4292 0.666626 20.2852 0.666626 13.9998 0.666626C7.7145 0.666626 4.57184 0.666626 2.6185 2.61863C0.666504 4.57329 0.666504 7.71463 0.666504 14C0.666504 20.2853 0.666504 23.428 2.6185 25.38C4.57317 27.3333 7.7145 27.3333 13.9998 27.3333C20.2852 27.3333 23.4278 27.3333 25.3798 25.38ZM17.7705 9.22929C18.0357 9.22929 18.2901 9.33465 18.4776 9.52219C18.6651 9.70972 18.7705 9.96408 18.7705 10.2293V15.8853C18.7705 16.1505 18.6651 16.4049 18.4776 16.5924C18.2901 16.7799 18.0357 16.8853 17.7705 16.8853C17.5053 16.8853 17.2509 16.7799 17.0634 16.5924C16.8759 16.4049 16.7705 16.1505 16.7705 15.8853V12.6426L10.9358 18.48C10.8443 18.5782 10.7339 18.657 10.6112 18.7117C10.4886 18.7663 10.3561 18.7957 10.2219 18.7981C10.0876 18.8004 9.95422 18.7757 9.82971 18.7255C9.70519 18.6752 9.59208 18.6003 9.49712 18.5053C9.40216 18.4104 9.3273 18.2973 9.27701 18.1728C9.22671 18.0482 9.20201 17.9149 9.20438 17.7806C9.20675 17.6463 9.23614 17.5139 9.2908 17.3912C9.34545 17.2686 9.42425 17.1582 9.5225 17.0666L15.3572 11.2306H12.1145C11.8493 11.2306 11.5949 11.1253 11.4074 10.9377C11.2199 10.7502 11.1145 10.4958 11.1145 10.2306C11.1145 9.96541 11.2199 9.71105 11.4074 9.52352C11.5949 9.33598 11.8493 9.23063 12.1145 9.23063H17.7705V9.22929Z" fill="currentColor"/>
                                </svg>
                            </p>
                        </div>
                        <div>
                            <p class="font-bold">
                                {{ $user->first_name . ' ' . $user->last_name }}
                                <span class="text-gray-600">({{ $user->id }})</span>
                            </p>
                            <p class="text-sm">{{ $user->email }}</p>
                            <p class="text-sm">{{ $user->prettyRole() }}</>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="w-1/2 bg-gray-100">
        @content()
    </div>
</div>
@endlayout
