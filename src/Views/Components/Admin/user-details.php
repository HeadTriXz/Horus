<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    @if($user !== null)
        <div class="w-full p-4 bg-black rounded-xl shadow-lg shadow-slate-200">
            <p class="text-sm text-white">{{ $user->email }}</p>
            <div class="flex flex-wrap mt-2 items-center font-bold">
                <p class="text-white truncate mr-2 max-w-xs">{{ $user->first_name . ' ' . $user->last_name }}</p>
                <p class="text-gray-300">({{ $user->id }})</p>
            </div>
        </div>

        <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
            <p class="font-bold">Role</p>
            <p>{{ $user->prettyRole() }}</p>
        </div>

        <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
            <p class="font-bold">Update user</p>
            <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('users.update', [ 'id' => $user->id ]) }}">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">First name</label>
                        <input type="text" name="first_name" id="first_name"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="{{ $user->first_name }}" value="{{ $user->first_name }}" required>
                    </div>
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Last name</label>
                        <input type="text" name="last_name" id="last_name"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="{{ $user->last_name }}" value="{{ $user->last_name }}" required>
                    </div>
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                    <input type="email" name="email" id="email"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="{{ $user->email }}" value="{{ $user->email }}" required>
                </div>
                @if(!empty($error))
                    <p class="mb-4 text-red-500">{{ $error }}</p>
                @endif
                <button type="submit" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                    Save changes
                </button>
            </form>
        </div>
    @endif
</aside>
