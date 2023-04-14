@layout('Admin/courses.php')
    @block('new-button')
        <a href="{{ route('courses') }}" class="text-secondary border-2 border-secondary hover:border-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2 text-center">
            Go back
        </a>
    @endblock
    @component('Admin/course-create.php') @endcomponent
@endlayout
