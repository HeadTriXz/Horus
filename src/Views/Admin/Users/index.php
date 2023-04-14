@layout('Admin/users.php')
    @block('new-button')
        <a href="{{ route('users.create') }}" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
            New user
        </a>
    @endblock

    @component('Admin/user-details.php', [ 'user' => $selected ]) @endcomponent
@endlayout
