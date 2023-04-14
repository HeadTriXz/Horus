@layout('Teacher/exams.php')
    @block('new-button')
        <a href="{{ route('exams.create') }}" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
            New exam
        </a>
    @endblock

    @component('Teacher/Exams/details.php', [ 'exam' => $selected ])
        @component('Admin/Exams/update.php') @endcomponent
    @endcomponent
@endlayout
