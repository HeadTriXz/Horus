@layout('Admin/exams.php')
    @component('Admin/grades-manage.php', [ 'exam' => $selected ]) @endcomponent
@endlayout
