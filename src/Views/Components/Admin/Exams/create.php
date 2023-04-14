<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    <h2 class="mb-4 text-2xl font-bold">Create exam</h2>
    <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
        <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('exams.store') }}">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                <input type="text" name="name" id="name"
                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                       placeholder="Project Xenobiology II" required>
            </div>
            <div>
                <label for="course" class="block mb-2 text-sm font-medium text-gray-900 ">Course</label>
                <select id="course" name="course" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="datetime" class="block mb-2 text-sm font-medium text-gray-900 ">Exam date</label>
                    <input type="datetime-local" name="datetime" id="datetime"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           required>
                </div>
                <div>
                    <label for="duration" class="block mb-2 text-sm font-medium text-gray-900 ">Duration (minutes)</label>
                    <input type="number" name="duration" id="duration"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="None">
                </div>
            </div>
            @if(!empty($error))
                <p class="mb-4 text-red-500">{{ $error }}</p>
            @endif
            <button type="submit" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                Create
            </button>
        </form>
    </div>
</aside>
