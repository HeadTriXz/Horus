@layout('Teacher/exams.php')
    @block('new-button') @endblock
    @component('Teacher/Exams/details.php', [ 'exam' => $selected ]) @endcomponent
@endlayout
