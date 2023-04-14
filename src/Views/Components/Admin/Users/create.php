<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    <h2 class="mb-4 text-2xl font-bold">Create user</h2>
    <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
        <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('users.store') }}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">First name</label>
                    <input type="text" name="first_name" id="first_name"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="John" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Last name</label>
                    <input type="text" name="last_name" id="last_name"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="Doe" required>
                </div>
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                <input type="email" name="email" id="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                       placeholder="j.doe@st.hanze.nl" required>
            </div>
            <div>
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 ">Role</label>
                <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    <option value="">Select a role</option>
                    <option value="0">Student</option>
                    <option value="1">Teacher</option>
                    <option value="2">Admin</option>
                </select>
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                <input type="password" name="password" id="password" minlength="10"
                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                       placeholder="••••••••" required>
            </div>
            <div>
                <p class="font-medium">Password requirements:</p>
                <p class="text-gray-500">Ensure that these requirements are met:</p>
                <ul class="ml-6 mb-4 list-disc text-sm text-gray-500">
                    <li>At least 10 characters (and up to 100 characters)</li>
                    <li>At least one lowercase character</li>
                    <li>At least one uppercase character</li>
                    <li>At least one number</li>
                    <li>Inclusion of at least one special character, e.g., ! @ # ?</li>
                </ul>
            </div>
            @if(!empty($error))
                <p class="mb-4 text-red-500">{{ $error }}</p>
            @endif
            <button type="submit" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                Create
            </button>
        </form>
    </div>
</aside>
