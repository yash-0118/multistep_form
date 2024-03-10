<x-layout>

    @php
    $email = session('form_data.email', " ");
    @endphp

    <div class="bg-white p-8 rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Step 1: Enter Email</h2>
        @if(session('error'))
        <div class="text-red-500">{{ session('error') }}</div>
        @endif

        <form action="{{ url('/form/step1') }}" method="post" class="space-y-4">
            @csrf
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" name="email" value="{{ $email }}" class="border rounded-md p-2 w-full">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Next</button>
        </form>
    </div>

</x-layout>