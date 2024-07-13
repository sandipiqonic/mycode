let baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
document.addEventListener('DOMContentLoaded', function() {
    let selectedMediaUrl = '';
    let currentImageContainer = '';
    let currentHiddenInput = '';
    let videoInputCounter = 0; // Initialize a counter for dynamic IDs

    function initializeImageSelection(button) {
        button.addEventListener('click', function() {
            currentImageContainer = this.getAttribute('data-image-container');
            currentHiddenInput = this.getAttribute('data-hidden-input');
        });
    }

    function initializeModal() {
        document.querySelectorAll('button[data-bs-target="#exampleModal"]').forEach(function(button) {
            initializeImageSelection(button);
        });
    }

    function selectMedia(mediaUrl, mediaElement) {
        selectedMediaUrl = mediaUrl;

        // Remove active class from all media elements
        document.querySelectorAll('#mediaLibraryContent img, #mediaLibraryContent video').forEach(function(media) {
            media.classList.remove('iq-image');
        });

        // Add active class to the selected media element
        mediaElement.classList.add('iq-image');
    }

    if (document.getElementById('mediaLibraryContent')) {
        document.getElementById('mediaLibraryContent').addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                var mediaUrl = event.target.src;
                selectMedia(mediaUrl, event.target);
            } else if (event.target.tagName === 'VIDEO') {
                var mediaUrl = event.target.querySelector('source').src;
                selectMedia(mediaUrl, event.target);
                event.preventDefault();
            }
        });
    }

    if (document.getElementById('mediaSubmitButton')) {
      document.getElementById('mediaSubmitButton').addEventListener('click', function() {
          if (selectedMediaUrl && currentImageContainer && currentHiddenInput) {
              var selectedImageContainer = document.getElementById(currentImageContainer);
              var mediaUrlInput = document.getElementById(currentHiddenInput);
              if (selectedImageContainer) {
                  mediaUrlInput.value = selectedMediaUrl;

                  selectedImageContainer.innerHTML = '';

                  // Check if there's an element with id iq-video-quality

                  if (mediaUrlInput.hasAttribute('data-validation')) {
                      // Only allow video selection
                      if (selectedMediaUrl.endsWith('.mp4') || selectedMediaUrl.endsWith('.avi')) {
                          var video = document.createElement('video');
                          video.src = selectedMediaUrl;
                          video.controls = true;
                          video.classList.add('img-fluid', 'mb-2');
                          video.style.maxWidth = '300px';
                          video.style.maxHeight = '300px';

                          selectedImageContainer.appendChild(video);

                          var crossIcon = document.createElement('span');
                          crossIcon.innerHTML = '&times;';
                          crossIcon.classList.add('remove-media-icon');
                          crossIcon.style.cursor = 'pointer';
                          crossIcon.style.fontSize = '24px';
                          crossIcon.style.marginLeft = '10px';
                          crossIcon.addEventListener('click', function() {
                              selectedImageContainer.innerHTML = '';
                              mediaUrlInput.value = '';
                          });

                          selectedImageContainer.appendChild(crossIcon);
                      } else {
                          // Show error for incorrect media type
                          var errorElement = document.createElement('div');
                          errorElement.classList.add('text-danger');
                          errorElement.textContent = 'Only video files are allowed.';
                          selectedImageContainer.appendChild(errorElement);
                      }
                  } else {

                    if(selectedMediaUrl.endsWith('.png') || selectedMediaUrl.endsWith('.jpg')){
                      // For other cases, default behavior (assuming image upload or other media)
                      var img = document.createElement('img');
                      img.src = selectedMediaUrl;
                      img.classList.add('img-fluid', 'mb-2');
                      img.style.maxWidth = '100px';
                      img.style.maxHeight = '100px';

                      selectedImageContainer.appendChild(img);

                      var crossIcon = document.createElement('span');
                      crossIcon.innerHTML = '&times;';
                      crossIcon.classList.add('remove-media-icon');
                      crossIcon.style.cursor = 'pointer';
                      crossIcon.style.fontSize = '24px';
                      crossIcon.style.marginLeft = '10px';
                      crossIcon.addEventListener('click', function() {
                          selectedImageContainer.innerHTML = '';
                          mediaUrlInput.value = '';
                      });

                      selectedImageContainer.appendChild(crossIcon);
                    } else {
                      var errorElement = document.createElement('div');
                          errorElement.classList.add('text-danger');
                          errorElement.textContent = 'Only image files are allowed.';
                          selectedImageContainer.appendChild(errorElement);
                    }

                  }


                  $('#exampleModal').modal('hide');
              }
          }
      });
  }

  if(document.getElementById('submitButton')){
    document.getElementById('submitButton').addEventListener('click', function(event) {
      event.preventDefault(); // Prevent the default form submission

      var formData = new FormData();
      var remainingFiles = window.uploadedFiles.filter(file => !file.removed);

      if (remainingFiles.length > 0) {
          for (var i = 0; i < remainingFiles.length; i++) {
              formData.append('file_url[]', remainingFiles[i].file);
          }

          // Submit the form with remaining files
          var xhr = new XMLHttpRequest();
          xhr.open('POST', `${baseUrl}/app/filemanagers/store`, true);
          xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // Set CSRF token header

          xhr.onload = function() {
            if (xhr.status === 200) {
                // Redirect or show success message after successful submission
                document.getElementById('nav-media-library-tab').click();

                fetch(`${baseUrl}/app/filemanagers/getMediaStore`) // Replace with your actual path to refresh media library content
                    .then(response => response.json())
                    .then(data => {
                        var mediaLibraryContent = document.getElementById('mediaLibraryContent');
                        mediaLibraryContent.innerHTML = ''; // Clear existing content

                        data.response.forEach(url => {
                          if (url.endsWith('.mp4') || url.endsWith('.avi')) {
                            var video = document.createElement('video');
                            video.src = url;
                            video.controls = true;
                            video.classList.add('img-fluid', 'mb-2');
                            video.style.maxWidth = '300px';
                            video.style.maxHeight = '300px';

                            mediaLibraryContent.appendChild(video);

                        } else {
                          var img = document.createElement('img');
                          img.classList.add('img-fluid');
                          img.src = url;
                          img.id = 'nav-media-img';
                          img.style.width = '10rem';
                          img.style.height = '10rem';
                          mediaLibraryContent.appendChild(img);
                        }
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        };

          xhr.send(formData);
      }

      if(window.location.href === `${baseUrl}/app/filemanagers`){
        window.location.reload();
      }
  });
  }
console.log(window.location.href === `${baseUrl}/app/filemanagers`)
    function destroySelect2(section) {
        section.find('select.select2').each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });
    }

    function initializeSelect2(section) {
        section.find('select.select2').each(function() {
            $(this).select2({
                width: '100%'
            });
        });
    }

    $('#add_more_video').click(function() {
        var originalSection = $('.video-inputs-container').first();
        destroySelect2(originalSection);

        var newSection = originalSection.clone();
        videoInputCounter++; // Increment the counter

        newSection.find('input, select').each(function() {
            var idAttr = $(this).attr('id');
            if (idAttr) {
                $(this).attr('id', idAttr + videoInputCounter);
            }

            var nameAttr = $(this).attr('name');
            if (nameAttr) {
                $(this).attr('name', nameAttr + videoInputCounter);
            }

            $(this).val('').trigger('change');
        });

        newSection.find('.remove-video-input').removeClass('d-none');

        newSection.find('[data-image-container]').each(function() {
            var dataAttr = $(this).attr('data-image-container');
            $(this).attr('data-image-container', dataAttr + videoInputCounter);
        });

        newSection.find('[data-hidden-input]').each(function() {
            var dataAttr = $(this).attr('data-hidden-input');
            $(this).attr('data-hidden-input', dataAttr + videoInputCounter);
        });

        newSection.find('.img-fluid').remove();
        newSection.find('.remove-media-icon').remove();
        newSection.find('input[type="hidden"]').val('');

        newSection.find('div[id]').each(function() {
            var idAttr = $(this).attr('id');
            if (idAttr) {
                $(this).attr('id', idAttr + videoInputCounter);
            }
        });

        $('#video-inputs-container-parent').append(newSection);

        initializeSelect2(newSection);
        initializeModal();
    });

    $(document).on('click', '.remove-video-input', function() {
        $(this).closest('.video-inputs-container').remove();
    });

    initializeModal();
    initializeSelect2($(document));
});




  if(document.getElementById('file_url_media')){
    document.getElementById('file_url_media').addEventListener('change', function() {
        var fileInput = document.getElementById('file_url_media');
        var uploadedImagesContainer = document.getElementById('uploadedImages');
        var chunkSize = 1024 * 1024 * 30; // 100 MB chunk size (adjust as necessary)
        var uploadedFiles = [];

        // Clear previously uploaded images and reset progress
        uploadedImagesContainer.innerHTML = '';

        if (fileInput.files.length > 0) {
            for (var i = 0; i < fileInput.files.length; i++) {
                var file = fileInput.files[i];
                var start = 0;
                var end = Math.min(chunkSize, file.size);
                var index = 0;

                if (file.type.startsWith('video/')) {
                    var video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.currentTime = 1; // Capture frame at 1 second

                    video.addEventListener('loadeddata', function() {
                        var canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        var img = document.createElement('img');
                        img.src = canvas.toDataURL('image/jpeg');
                        img.classList.add('img-fluid', 'iq-uploaded-image');
                        img.style.width = '150px'; // Adjust size as needed
                        img.style.height = '100px';

                        // Create progress bar
                        var progressBar = document.createElement('div');
                        progressBar.classList.add('progress', 'mb-3', 'iq-progress');
                        progressBar.innerHTML = `
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        `;

                        // Create close icon
                        var closeButton = document.createElement('div');
                        closeButton.classList.add('iq-uploaded-image-close');
                        closeButton.innerHTML = '&times;';
                        closeButton.addEventListener('click', function() {
                            uploadedFiles[index].removed = true; // Mark file as removed
                            this.parentNode.remove(); // Remove image on close icon click
                        });

                        // Append image, progress bar, and close icon
                        var imageContainer = document.createElement('div');
                        imageContainer.classList.add('iq-uploaded-image-container', 'd-flex', 'gap-5', 'align-items-center');
                        imageContainer.appendChild(img);
                        imageContainer.appendChild(progressBar);
                        imageContainer.appendChild(closeButton);
                        uploadedImagesContainer.appendChild(imageContainer);

                        // Track the uploaded file
                        uploadedFiles.push({ file: file, removed: false, progressBar: progressBar.querySelector('.progress-bar') });

                        uploadChunk(file, index, start, end, chunkSize, uploadedFiles);
                    });
                } else {
                    var reader = new FileReader();
                    reader.onload = (function(file, index) {
                        return function(e) {
                            var img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('img-fluid', 'iq-uploaded-image');
                            img.style.width = '150px'; // Adjust size as needed
                            img.style.height = '100px';

                            // Create progress bar
                            var progressBar = document.createElement('div');
                            progressBar.classList.add('progress', 'mb-3', 'iq-progress');
                            progressBar.innerHTML = `
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            `;

                            // Create close icon
                            var closeButton = document.createElement('div');
                            closeButton.classList.add('iq-uploaded-image-close');
                            closeButton.innerHTML = '&times;';
                            closeButton.addEventListener('click', function() {
                                uploadedFiles[index].removed = true; // Mark file as removed
                                this.parentNode.remove(); // Remove image on close icon click
                            });

                            // Append image, progress bar, and close icon
                            var imageContainer = document.createElement('div');
                            imageContainer.classList.add('iq-uploaded-image-container', 'd-flex', 'gap-5', 'align-items-center');
                            imageContainer.appendChild(img);
                            imageContainer.appendChild(progressBar);
                            imageContainer.appendChild(closeButton);
                            uploadedImagesContainer.appendChild(imageContainer);

                            // Track the uploaded file
                            uploadedFiles.push({ file: file, removed: false, progressBar: progressBar.querySelector('.progress-bar') });

                            uploadChunk(file, index, start, end, chunkSize, uploadedFiles);
                        };
                    })(file, i);

                    reader.readAsDataURL(file);
                }
            }
        }

        // Track the uploaded files globally
        window.uploadedFiles = uploadedFiles;
    });
  }

  function uploadChunk(file, index, start, end, chunkSize, uploadedFiles) {
    var chunk = file.slice(start, end);
    var formData = new FormData();
    formData.append('file_chunk', chunk);
    formData.append('index', index);
    formData.append('total_chunks', Math.ceil(file.size / chunkSize));
    formData.append('file_name', file.name);

    // AJAX request to upload chunk
    var xhr = new XMLHttpRequest();

    // Track upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            uploadedFiles[index].progressBar.style.width = percentComplete + '%';
        }
    });

    xhr.open('POST', `${baseUrl}/app/filemanagers/upload`, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content')); // Set CSRF token header

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                if (end < file.size) {
                    start = end;
                    end = Math.min(start + chunkSize, file.size);
                    uploadChunk(file, index, start, end, chunkSize, uploadedFiles);
                } else {
                    uploadedFiles[index].progressBar.style.width = '100%';
                }
            }
        }
    };

    xhr.send(formData);
  }
