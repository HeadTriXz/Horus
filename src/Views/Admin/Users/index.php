@layout('Admin/users.php')
    @component('Admin/user-details.php', [ 'user' => $selected ]) @endcomponent
@endlayout
