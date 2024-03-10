<x-layout>
    <div class="bg-white p-8 rounded-md shadow-md  w-full">
        <h2 class="text-2xl font-semibold mb-4">Step 5: Declaration</h2>

        @if(session('error'))
        <div class="text-red-500">
            {{ session('error') }}
        </div>
        @endif

        @php
        if(isset(session('form_data')['file_name'])){
        $filename=session('form_data')['file_name'];
        }
        else{
        $filename=null;
        }

        $declaration = session('form_data.declaration', false);
        if($declaration==='on'){
        $declaration=true;
        }
        @endphp

        <p class="mb-4">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Culpa accusantium dolorem soluta, illum alias incidunt, asperiores ducimus et vitae atque doloribus nemo dolore eligendi consequuntur ad tenetur aut impedit error dicta tempora officiis dolor laborum rerum dignissimos. Corrupti nostrum alias facilis saepe aspernatur consequatur odit facere iste, at eius voluptates?
        </p>

        <form action="{{ url('/form/step5') }}" method="post" enctype="multipart/form-data" id="demoForm" class="space-y-4">
            @csrf

            <label class="flex items-center">
                <input type="checkbox" name="declaration" {{ ($declaration ?? false) ? 'checked' : '' }} required class="mr-2">
                I agree to the terms and conditions.
            </label>

            <label for="pdf" class="block">Upload PDF:</label>
            <input type="file" name="pdf" id="pdf" class="border rounded-md p-2 w-full">

            @if ($filename)
            <li id="msg" class="text-green-500">
                File name: {{ $filename }}
            </li>
            @endif

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Next</button>
        </form>

        <a href="{{ url('/form/step4') }}" class="mt-4 inline-block">
            <button class="bg-gray-300 px-4 py-2 rounded-md">Previous</button>
        </a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
            var msg = document.getElementById('msg').style;

            $("#pdf").change(function() {
                // Hide the file name when a new file is selected
                $("#msg").toggle(!this.files.length);
            });

            $("#demoForm").validate({
                rules: {
                    pdf: {
                        extension: "pdf",
                        filesize: 10242880,
                    },
                },
                messages: {
                    pdf: {
                        filesize: "File must be less than 10MB",
                        extension: "File must be in PDF format",
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, 'File size must be less than {0}');

            $('#pdf').rules('add', {
                filesize: 10242880
            });
        });
    </script>
</x-layout>