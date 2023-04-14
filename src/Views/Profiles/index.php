@layout('app.php')
<div class="p-4 sm:ml-64">
    <h1 class="mb-4 text-2xl font-bold">User settings</h1>
    <div class="p-4 mb-4 bg-white rounded-xl shadow-lg shadow-slate-200">
        <h2 class="mb-4 text-xl font-bold">General information</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="font-bold">Name</p>
                <p>{{ $user->first_name . ' ' . $user->last_name }}</p>
            </div>
            <div>
                <p class="font-bold">Account number</p>
                <p>{{ $user->id }}</p>
            </div>
            <div>
                <p class="font-bold">Email</p>
                <p>{{ $user->email }}</p>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
        <h2 class="mb-4 text-xl font-bold">Password information</h2>
        <form method="POST" action="{{ route('password.update') }}">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900">Current password</label>
                    <input type="password" name="current_password" id="current_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">New password</label>
                    <input type="password" name="new_password" id="new_password" minlength="10" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900">Confirm password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
            </div>
            <p class="font-medium">Password requirements:</p>
            <p class="text-gray-500">Ensure that these requirements are met:</p>
            <ul class="ml-6 mb-6 list-disc text-sm text-gray-500">
                <li>At least 10 characters (and up to 100 characters)</li>
                <li>At least one lowercase character</li>
                <li>At least one uppercase character</li>
                <li>At least one number</li>
                <li>Inclusion of at least one special character, e.g., ! @ # ?</li>
            </ul>

            @if(!empty($error))
                <p class="mb-4 text-red-500">{{ $error }}</p>
            @endif
            <button type="submit" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">Update password</button>
        </form>
    </div>
</div>
@endlayout
