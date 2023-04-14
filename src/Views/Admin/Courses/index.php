@layout('Admin/courses.php')
    @component('Admin/course-details.php', [ 'course' => $selected ]) @endcomponent
@endlayout
