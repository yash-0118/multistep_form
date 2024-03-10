<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- Add the following lines for Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    @php
    $email = session('form_data.email', " ");
    @endphp


    <div class="mt-8 bg-white p-8 rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Step 2: Verify OTP</h2>

        @if(session('error'))
        <div class="text-red-500">{{ session('error') }}</div>
        @endif

        <form action="{{ url('/form/step2') }}" method="post" class="space-y-4">
            @csrf
            <label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP sent to {{ session('form_data')['email'] }}:</label>
            <input type="text" name="otp" required class="border rounded-md p-2 w-full">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Verify</button>
        </form>

        <a href="{{ url('/form/step1') }}" class="mt-4 inline-block"><button class="bg-gray-300 px-4 py-2 rounded-md">Previous</button></a>
    </div>

</body>

</html>