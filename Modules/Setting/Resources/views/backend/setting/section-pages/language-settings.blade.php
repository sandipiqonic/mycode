@extends('setting::backend.setting.index')

@section('settings-content')

<div class="container">
    <div class="col-md-12 d-flex justify-content-between">
        <h4><i class="fa fa-language"></i> {{ __('setting_sidebar.lbl_language') }}</h4>

    </div>

    <form id="company_form" method="POST">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <label class="form-label">{{ __('setting_language_page.lbl_language') }}<span class="text-danger">*</span></label>
                    <select id="language_id" name="language_id" class="form-control">
                        <option value="" disabled {{ old('language_id') ? '' : 'selected' }}>Select Language</option>
                        @foreach($languages as $language)
                            <option value="{{ $language['id'] }}" {{ old('language_id') == $language['id'] ? 'selected' : '' }}>
                                {{ $language['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('language_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col">
                    <label class="form-label">{{ __('setting_language_page.lbl_file') }}<span class="text-danger">*</span></label>
                    <select id="file_id" name="file_id" class="form-control">
                        <option value="">{{ __('Select File') }}</option>
                        <!-- Options will be dynamically loaded here -->
                    </select>
                    @error('file_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>


        <div class="container py-3">
            <div class="row">
                <div class="col">
                    <h6>
                        <label class="form-label">{{ __('setting_language_page.lbl_key') }}</label>
                    </h6>
                </div>
                <div class="col">
                    <h6>
                        <label class="form-label">{{ __('setting_language_page.lbl_value') }}</label>
                    </h6>
                </div>
            </div>

        <div class="container py-3" id="translation-keys">



            <!-- Translation keys will be dynamically loaded here -->

        </div>
        </div>

        <button type="submit" class="btn btn-primary" id="form_btn">{{ __('messages.save') }}</button>
    </form>
</div>
@endsection
@push('after-scripts')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('language_id').addEventListener('change', function() {
            var languageId = this.value;
            fetchFiles(languageId);
        });

        document.getElementById('file_id').addEventListener('change', function() {
            var fileId = this.value;
            var languageId = document.getElementById('language_id').value;
            fetchLangData(fileId, languageId);
        });

        function fetchFiles(languageId) {
            fetch(`{{ route('backend.languages.array_list') }}?language_id=${languageId}`)
                .then(response => response.json())
                .then(data => {
                    let fileSelect = document.getElementById('file_id');
                    fileSelect.innerHTML = '<option value="">{{ __('Select File') }}</option>';
                    data.forEach(file => {
                        let option = document.createElement('option');
                        option.value = file.id;
                        option.textContent = file.name;
                        fileSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching files:', error);
                    // Handle error if needed
                });
            }

        function fetchLangData(fileId, languageId) {
            fetch(`{{ route('backend.languages.get_file_data') }}?file_id=${fileId}&language_id=${languageId}`)
                .then(response => response.json())
                .then(data => {
                    let container = document.getElementById('translation-keys');
                    container.innerHTML = '';
                    data.forEach(item => {
                        let row = document.createElement('div');
                        row.className = 'row';

                        let keyCol = document.createElement('div');
                        keyCol.className = 'col';
                        let keyGroup = document.createElement('div');
                        keyGroup.className = 'form-group';
                        let keyInput = document.createElement('input');
                        keyInput.type = 'text';
                        keyInput.className = 'form-control';
                        keyInput.value = item.key;
                        keyInput.disabled = true;
                        keyGroup.appendChild(keyInput);
                        keyCol.appendChild(keyGroup);

                        let valueCol = document.createElement('div');
                        valueCol.className = 'col';
                        let valueGroup = document.createElement('div');
                        valueGroup.className = 'form-group';
                        let valueInput = document.createElement('input');
                        valueInput.type = 'text';
                        valueInput.name = `lang_data[${item.key}]`;
                        valueInput.className = 'form-control';
                        valueInput.value = item.value;
                        valueGroup.appendChild(valueInput);
                        valueCol.appendChild(valueGroup);

                        row.appendChild(keyCol);
                        row.appendChild(valueCol);

                        container.appendChild(row);
                    });
                });
        }
    });

    function submitForm(event) {
        event.preventDefault(); // Prevent the default form submission behavior

        // Get language_id and file_id
        let languageId = document.getElementById('language_id').value;
        let fileId = document.getElementById('file_id').value;

        // Initialize an array to hold the formatted data
        let formattedData = [];

        // Get all input fields inside #translation-keys
        let dataInputs = document.querySelectorAll('#translation-keys input[type="text"]');

        // Iterate over each input field
        dataInputs.forEach(input => {
            // Extract key and value from input name and value
            let key = input.name.replace('lang_data[', '').replace(']', '');
           let value = input.value;
            // Skip if key or value is empty
            if (!key || !value) {
                return;
            }

            // Construct the data object with key, value, languageId, and fileId
            let dataObj = {
                key: key,
                value: value,
                language: languageId,
                file: fileId
            };

            // Push the data object to formattedData array
            formattedData.push(dataObj);
        });

        // Prepare the payload as JSON
        const payload = JSON.stringify({
            language_id: languageId,
            file_id: fileId,
            data: formattedData });

        // Send the FormData via fetch
        fetch('{{ route('backend.languages.store') }}', {
            method: 'POST',
            body: payload,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json' // Set content type to JSON
            }
        })
        .then(response => response.json())
        .then(data => {
            // Handle response as needed, e.g., show success message
            console.log(data);
            alert('Data saved successfully!');
        })
        .catch(error => {
            console.error('Error saving data:', error);
            // Handle error if needed
            alert('Error saving data. Please try again.');
        });
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //     var form = document.querySelector('form');
    //     form.addEventListener('submit', submitForm);
    // });

    $("#form_btn").click(function(e){
        console.log("submit")
        e.preventDefault();
        submitForm(e)
    });
</script>

@endpush

