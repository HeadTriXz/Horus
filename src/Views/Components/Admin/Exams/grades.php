<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    @if($exam !== null)
        <div>
            <div class="w-full p-4 bg-black rounded-xl shadow-lg shadow-slate-200">
                <div class="flex flex-wrap font-bold text-sm items-center">
                    <p class="text-white truncate mr-2 max-w-[34rem]">{{ $exam->course()->name }}</p>
                    <p class="text-gray-300">({{ $exam->course()->code }})</p>
                </div>
                <p class="text-white font-bold">{{ $exam->name }}</p>
            </div>
            <div class="mt-6">
                <h2 class="font-bold mb-4">Manage grades</h2>
                <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('grades.update', [ 'id' => $exam->id ]) }}">
                    <div class="relative overflow-x-auto shadow-md rounded-lg">
                        <table class="table-auto w-full text-sm text-left">
                            <thead class="text-xs text-white uppercase bg-black">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Student number</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $user)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-3">{{ $user->id }}</td>
                                        <td class="px-6 py-3">{{ $user->first_name . ' ' . $user->last_name }}</td>
                                        <td class="px-6 py-3">
                                            <label for="g-{{ $user->id }}"></label>
                                            <input id="g-{{ $user->id }}" name="g-{{ $user->id }}" max="10" min="1" step="0.1"
                                                   type="number" placeholder="None" value="{{ $user->grade }}"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">
                        Save changes
                    </button>
                </form>
            </div>
        </div>
    @endif
</aside>
