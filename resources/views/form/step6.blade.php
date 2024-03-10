<x-layout>
    <div class="bg-white p-8 rounded-md shadow-md  w-full">
        <h2 class="text-2xl font-semibold mb-4">Step 6: Review and Submit</h2>

        @if(session('error'))
        <div class="text-red-500">
            {{ session('error') }}
        </div>
        @endif

        <p>Review your information and click Submit to complete the form.</p>

        <h2 class="text-xl font-semibold mt-4">Stored Data</h2>
        <ul class="list-disc ml-6 mt-2">
            <li>Email: {{ $storedData['email'] }}</li>
            <li>
                Property Details:
                <ul class="list-disc ml-6">
                    @foreach($storedData['property'] as $property)
                    <li>
                        <strong>Property Number:</strong> {{ $property['property_number'] }}<br>
                        <strong>Business Name:</strong> {{ $property['business_name'] }}<br>
                        <strong>Address:</strong> {{ $property['address'] }}<br>
                        <strong>Business TRN:</strong> {{ $property['business_trn'] }}
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                Bank Details:
                <ul class="list-disc ml-6">
                    <li>Bank Name: {{ $storedData['bank_details']['bank_name'] }}</li>
                    <li>Account Number: {{ $storedData['bank_details']['account_number'] }}</li>
                    <li>Branch: {{ $storedData['bank_details']['branch'] }}</li>
                    <li>IFSC Code: {{ $storedData['bank_details']['ifsc_code'] }}</li>
                </ul>
            </li>
            @if ($storedData['file_name'])
            <li>
                File name : {{ $storedData['file_name'] }}
            </li>
            @endif
        </ul>

        <form action="{{ url('/form/step6') }}" method="post" class="mt-6">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Submit</button>
        </form>

        <a href="{{ url('/form/step5') }}" class="mt-4 inline-block">
            <button class="bg-gray-300 px-4 py-2 rounded-md">Previous</button>
        </a>
    </div>
</x-layout>