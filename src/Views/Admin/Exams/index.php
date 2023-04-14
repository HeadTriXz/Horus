@layout('Admin/exams.php')
    @block('new-button')
        <a href="{{ route('exams.create') }}" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
            New exam
        </a>
    @endblock

    @component('Admin/exam-details.php', [ 'exam' => $selected ])
        @component('Admin/exam-update.php') @endcomponent
    @endcomponent
@endlayout
