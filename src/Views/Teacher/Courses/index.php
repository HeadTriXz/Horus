@layout('Teacher/courses.php')
    @block('new-button') @endblock
    @component('Teacher/Courses/details.php', [ 'course' => $selected ]) @endcomponent
@endlayout
