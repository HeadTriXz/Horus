@layout('Teacher/exams.php')
    @block('new-button')
        <a href="{{ route('exams', [ 'e' => $selected->id ]) }}" class="text-secondary border-2 border-secondary hover:border-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2 text-center">
            Go back
        </a>
    @endblock

    @component('Teacher/Exams/grades.php', [ 'exam' => $selected ]) @endcomponent
@endlayout
