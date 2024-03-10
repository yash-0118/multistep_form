<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Add the following lines for Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

    @php
    $bankDetails = session('form_data.bank_details', []);
    @endphp

    <div class="bg-white p-8 rounded-md shadow-md max-w-md w-full">
        <h2 class="text-2xl font-semibold mb-4">Step 4: Add Bank Details</h2>

        @if(session('error'))
        <div class="text-red-500">
            {{ session('error') }}
        </div>
        @endif


        <form action="{{ url('/form/step4') }}" method="post" class="space-y-4">
            @csrf

            <label for="bank_name" class="block">Bank Name:</label>
            <input type="text" name="bank_name" value="{{ $bankDetails['bank_name'] ?? '' }}" required class="border rounded-md p-2 w-full">

            <label for="account_number" class="block">Account Number:</label>
            <input type="number" name="account_number" value="{{ $bankDetails['account_number'] ?? '' }}" required class="border rounded-md p-2 w-full">

            <label for="branch" class="block">Branch:</label>
            <input type="text" name="branch" value="{{ $bankDetails['branch'] ?? '' }}" required class="border rounded-md p-2 w-full">

            <label for="ifsc_code" class="block">IFSC Code:</label>
            <input type="text" name="ifsc_code" value="{{ $bankDetails['ifsc_code'] ?? '' }}" required class="border rounded-md p-2 w-full">

            <br>
            <br>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Next</button>
        </form>

        <a href="{{ url('/form/step3') }}" class="mt-4 inline-block"><button class="bg-blue-500 text-white px-4 py-2 rounded-md">Previous</button></a>

    </div>

</body>

</html>