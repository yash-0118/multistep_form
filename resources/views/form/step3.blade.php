<x-layout>
    <h2 class="text-2xl font-semibold mb-4">Step 3: Add Property Details</h2>

    @if(session('error'))
    <div class="text-red-500">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ url('/form/step3') }}" method="post" onsubmit="return checkPropertyNumbers()" class="space-y-4">
        @csrf

        <div id="property-sections" class="space-y-4">
            @php
            $propertyData = session('form_data.property', []);
            @endphp

            @if(empty($propertyData))
            <div class="flex space-x-4 items-center property-section">
                <div class="w-1/4">
                    <label for="property_number" class="block">Property Number:</label>
                    <input type="number" name="property_number[]" value="" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="business_name" class="block">Business Name:</label>
                    <input type="text" name="business_name[]" value="" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="address" class="block">Address:</label>
                    <input type="text" name="address[]" value="" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="business_trn" class="block">Business TRN:</label>
                    <input type="text" name="business_trn[]" value="" required class="border rounded-md p-2 w-full">
                </div>

                <button type="button" onclick="deletePropertySection(this)" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
            </div>
            @else
            @foreach($propertyData as $index => $property)
            <div class="flex space-x-4 items-center property-section">
                <div class="w-1/4">
                    <label for="property_number" class="block">Property Number:</label>
                    <input type="number" name="property_number[]" value="{{ $property['property_number'] }}" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="business_name" class="block">Business Name:</label>
                    <input type="text" name="business_name[]" value="{{ $property['business_name'] }}" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="address" class="block">Address:</label>
                    <input type="text" name="address[]" value="{{ $property['address'] }}" required class="border rounded-md p-2 w-full">
                </div>

                <div class="w-1/4">
                    <label for="business_trn" class="block">Business TRN:</label>
                    <input type="text" name="business_trn[]" value="{{ $property['business_trn'] }}" required class="border rounded-md p-2 w-full">
                </div>

                <button type="button" onclick="deletePropertySection(this)" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
            </div>
            @endforeach
            @endif
        </div>

        <button type="button" onclick="addPropertySection()" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Add Property</button>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md mt-4">Next</button>
    </form>

    <a href="/form/part3" class="mt-4 inline-block"><button disabled class="bg-gray-300 px-4 py-2 rounded-md">Previous</button></a>

    <script>
        function checkPropertyNumbers() {
            var propertyNumbers = document.querySelectorAll('[name^="property_number[]"]');
            var uniquePropertyNumbers = new Set();

            for (var i = 0; i < propertyNumbers.length; i++) {
                var propertyNumber = propertyNumbers[i].value;

                if (uniquePropertyNumbers.has(propertyNumber)) {
                    alert("Property numbers must be unique. Please enter a unique property number.");
                    return false;
                }

                uniquePropertyNumbers.add(propertyNumber);
            }

            return true;
        }

        function addPropertySection() {
            var propertySection = document.querySelector('.property-section');
            var addsection = propertySection.cloneNode(true);
            addsection.querySelectorAll('input').forEach(function(input) {
                input.value = '';
            });

            var existingDeleteButton = addsection.querySelector('button');
            if (existingDeleteButton) {
                existingDeleteButton.remove();
            }

            var deleteButton = document.createElement('button');
            deleteButton.setAttribute('type', 'button');
            deleteButton.setAttribute('onclick', 'deletePropertySection(this)');
            deleteButton.textContent = 'Delete';
            deleteButton.classList.add('bg-red-500', 'text-white', 'px-4', 'py-2', 'rounded-md');

            addsection.appendChild(deleteButton);

            document.getElementById('property-sections').appendChild(addsection);
        }

        function deletePropertySection(button) {
            var propertySection = button.parentElement;
            var propertySections = document.getElementById('property-sections');

            // Ensure at least one record field is present
            if (propertySections.children.length > 1) {
                propertySections.removeChild(propertySection);
            } else {
                alert("At least one property field must be present.");
            }
        }
    </script>
</x-layout>