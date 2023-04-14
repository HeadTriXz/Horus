@layout('Admin/courses.php')
    @block('new-button')
        <a href="{{ route('courses.create') }}" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
            New course
        </a>
    @endblock

    @component('Admin/course-details.php', [ 'course' => $selected ])
        @component('Admin/course-update.php') @endcomponent
    @endcomponent
@endlayout
