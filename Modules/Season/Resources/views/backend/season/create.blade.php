@extends('backend.layouts.app')
@section('content')
<div class="mb-3">
    <div class="text-danger" id="error_message"></div>
    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div class="flex-grow-1">
            <div class="row">
                <div class="col-lg-6">
                    {{ html()->label(__('movie.tvshows'), 'tvshows')->class('form-label') }}
                    {{ html()->select(
                        'tv_show_id',
                        $imported_tvshow->pluck('name', 'tmdb_id')->prepend('Select TvShow', null),

                    )->class('form-control select2')->id('tv_show_id') }}

                    <span class="text-danger" id="tvshow_id_error"></span>
                </div>    
                <div class="col-lg-6">
                    {{ html()->label(__('movie.seasons'), 'seasons')->class('form-label') }}
                    {{ html()->select(
                        'season_id',
                        null,

                    )->class('form-control select2')->id('season_id') }}
                    <span class="text-danger" id="season_id_error"></span>
                </div>
            </div>
        </div>
        <div>
            <div id="loader" style="display: none;">
                <button class="btn btn-md btn-primary float-right">Loading...</button>
            </div>
            <button class="btn btn-md btn-primary float-right" id="import_season_id">Import</button>
        </div>
    </div>    
</div>

<div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
    <h6>About TV shows seasons</h6>
</div>

{{ html()->form('POST' ,route('backend.seasons.store'))
    ->attribute('enctype', 'multipart/form-data')
    ->attribute('data-toggle', 'validator')
    ->open()
}}
<div class="card">
    <div class="card-body">        
        <div class="row">
            {{ html()->hidden('season_index', null)->id('season_index') }}
            {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}
            <div class="col-lg-6 mb-3 position-relative">
                {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label form-control-label') }}
                <div class="input-group btn-file-upload">
                    {{ html()->button('Select')
                        ->class('input-group-text form-control')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerPoster')
                        ->attribute('data-hidden-input', 'file_url_poster')
                    }}

                    {{ html()->text('poster_input')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Poster Image')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerPoster')
                        ->attribute('data-hidden-input', 'file_url_poster')
                    }}
                </div>
                <div class="mt-3" id="selectedImageContainerPoster">
                    @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                        <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>
                {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label form-control-label') }}
                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_season_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('season.lbl_tv_shows') . ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                {{ html()->select(
                        'entertainment_id',
                        $tvshows->pluck('name', 'id')->prepend('Select TvShow', old('entertainment_id')),

                    )->class('form-control select2')->id('entertainment_id') }}
                @error('entertainment_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                {{ html()->select(
                        'trailer_url_type',
                        $upload_url_type->pluck('name', 'value')->prepend('Select Type', ''),
                        old('trailer_url_type', 'Local'), // Set 'Local' as the default value
                    )->class('form-control select2')->id('trailer_url_type') }}
                @error('trailer_url_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 d-none" id="url_input">
                {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-label form-control-label') }}
                {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                @error('trailer_url')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 d-none" id="url_file_input">
                {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-label form-control-label') }}

                <div class="input-group btn-file-upload">
                    {{ html()->button('Select')
                        ->class('input-group-text form-control')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainertailerurl')
                        ->attribute('data-hidden-input', 'file_url_trailer')
                    }}

                    {{ html()->text('trailer_input')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Trailer Image')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainertailerurl')
                        ->attribute('data-hidden-input', 'file_url_trailer')
                    }}
                </div>

                <div class="mt-3" id="selectedImageContainertailerurl">
                    @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                        <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                {{ html()->hidden('trailer_url')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->poster_url : '')) }}
                {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'access')->class('form-label form-control-label') }}
                <div class="d-flex align-items-center">
                    <div class="form-check form-check-inline form-control px-5">
                        <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                            onchange="showPlanSelection(this.value === 'paid')"
                            {{ old('access') == 'paid' ? 'checked' : '' }} checked>
                        <label class="form-check-label" for="paid">Paid</label>
                    </div>
                    <div class="form-check form-check-inline form-control px-5">
                        <input class="form-check-input" type="radio" name="access" id="free" value="free"
                            onchange="showPlanSelection(this.value === 'paid')"
                            {{ old('access') == 'free' ? 'checked' : '' }}>
                        <label class="form-check-label" for="free">Free</label>
                    </div>
                </div>
                @error('access')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3 d-none" id="planSelection">
                {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                <div>
                    {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend('Select Plan', ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                </div>
                @error('plan_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                <div class="d-flex justify-content-between align-items-center form-control">
                    {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label text-body mb-0') }}
                    <div class="form-check form-switch">
                        {{ html()->hidden('status', 0) }}
                        {{
                            html()->checkbox('status', old('status', 1))
                                ->class('form-check-input')
                                ->id('status')
                                ->value(1)
                        }}
                    </div>
                </div>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
                {{ html()->textarea('short_desc', old('short_desc'))->class('form-control')->id('short_desc')->placeholder(__('placeholder.lbl_season_short_desc')) }}
                @error('short_desc')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_season_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="d-grid d-sm-flex justify-content-sm-end gap-3 mb-5">
    <a href="{{ route('backend.seasons.index') }}" class="btn btn-secondary">Close</a>
    {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
</div>

{{ html()->form()->close() }}
@include('components.media-modal')
@endsection
@push('after-scripts')
<script>

        document.addEventListener('DOMContentLoaded', function() {

            function handleTrailerUrlTypeChange(selectedValue) {
                var FileInput = document.getElementById('url_file_input');
                var URLInput = document.getElementById('url_input');

                if (selectedValue === 'Local') {
                    FileInput.classList.remove('d-none');
                    URLInput.classList.add('d-none');
                } else if (selectedValue === 'URL' || selectedValue === 'YouTube' || selectedValue === 'HLS' ||
                    selectedValue === 'Vimeo') {
                    URLInput.classList.remove('d-none');
                    FileInput.classList.add('d-none');
                } else {
                    FileInput.classList.add('d-none');
                    URLInput.classList.add('d-none');
                }
            }

            var initialSelectedValue = document.getElementById('trailer_url_type').value;
            handleTrailerUrlTypeChange(initialSelectedValue);
            $('#trailer_url_type').change(function() {
                var selectedValue = $(this).val();
                handleTrailerUrlTypeChange(selectedValue);
            });
        });

        function showPlanSelection(show) {
            var planSelection = document.getElementById('planSelection');
            if (show) {
                planSelection.classList.remove('d-none');
            } else {
                planSelection.classList.add('d-none');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var movieAccessPaid = document.getElementById('paid');
            if (movieAccessPaid.checked) {
                showPlanSelection(true);
            }
        });


      // ///////////////////////////////////////////////   get season list ///////////////////////////////////////

      function getSeasons(tmdbId, selectedSeasonId = "") {
                   var get_seasons_list = "{{ route('backend.seasons.import-season-list', ['tmdb_id' => '']) }}" + tmdbId;
                   get_seasons_list = get_seasons_list.replace('amp;', '');
            
                   $.ajax({
                       url: get_seasons_list,
                       success: function(result) {
            
                           var formattedResult = result.map(function(season) {
                               return { id: season.season_number, text: season.name };
                           });
            
                           $('#season_id').select2({
                               width: '100%',
                               placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}",
                               data: formattedResult
                           });
            
                           if (selectedSeasonId != "") {
                               $('#season_id').val(selectedSeasonId).trigger('change');
                           }
                       }
                   });
                }


            $(document).ready(function() {
             $('#tv_show_id').change(function() {
                var tvShowId = $(this).val();
                if (tvShowId) {
                    $('#season_id').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
                    });
                    getSeasons(tvShowId);
                } else {
                    $('#season_id').empty().select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
                    });
                }
            });
        });


       /////////////////////////////////////////////////   Import Season Data ///////////////////////////////////////  
       
       
     $(document).ready(function() {

    $('#import_season_id').on('click', function(e) {
       e.preventDefault();
    
       var tvshowID = $('#tv_show_id').val();
       $('#tvshow_id_error').text('');
       $('#error_message').text('');


       var seasonID = $('#season_id').val();
       $('#season_id_error').text('');
       $('#error_message').text('');

       var import_season = "{{ route('backend.seasons.import-season') }}";
           import_season = import_season.replace('amp;', '');
    
       if (!tvshowID) {
           $('#tvshow_id_error').text('TV show ID is required.');
           return;
       }

       if (!seasonID) {
           $('#season_id_error').text('Season is required.');
           return;
       }
    
       $('#loader').show();
       $('#import_season_id').hide();
    
       $.ajax({
           url: import_season,
           type: 'POST',
           headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           data: {
                   tvshow_id: tvshowID,
                   season_id: seasonID,
                 },
           success: function(response) {
    
               $('#loader').hide();
               $('#import_season_id').show();
    
               if(response.success){
    
                var data = response.data;
    
                $('#season_index').val(data.season_index);
                $('#tmdb_id').val(data.tvshow_id);
                $('#selectedPosterImage').attr('src', data.poster_url).show();
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#trailer_url_type').val(data.trailer_url_type).trigger('change');
                $('#trailer_url').val(data.trailer_url);
                $('#poster_url').val(data.poster_url);
                $('#entertainment_id').val(data.entertainment_id).trigger('change');

                 if(data.poster_url) {    
                      $('#selectedPosterImage').attr('src', data.poster_url).show();
                  }
                  if (data.access === 'paid') {
                    document.getElementById('paid').checked = true;
                    showPlanSelection(true);
                  } else {    
                    document.getElementById('free').checked = true;
                    showPlanSelection(false);
                  }
    
               } else {
                   $('#error_message').text(response.message || 'Failed to import movie details.');
               }
           },
           error: function(xhr) {
            $('#loader').hide();
               $('#import_season_id').show();
    
               console.log(xhr);
               $('#loader').hide();
               $('#import_movie').show();
               if (xhr.responseJSON && xhr.responseJSON.message) {
                   $('#error_message').text(xhr.responseJSON.message);
               } else {
                   $('#error_message').text('An error occurred while fetching the movie details.');
               }
            }
          });
      });
  });
        


</script>
@endpush
