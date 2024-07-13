@extends('backend.layouts.app')

@section('content')
<div class="container">
    <div class="movie-details">
        <div class="poster">
            <img src="{{ $data->poster_url }}" alt="{{ $data->name }}">
        </div>
        <div class="details">
            <h1>{{ $data->name }}</h1>
            <p>{{ $data->description }}</p>

            <div class="info">
                <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($data->release_date)->format('d M, Y') }}</p>
                <p><strong>Duration:</strong> {{ $data->duration }}</p>
                <p><strong>Trailer URL:</strong> <a href="{{ $data->trailer_url }}" target="_blank">{{ $data->trailer_url }}</a></p>
            </div>
            <div class="movie-info">
                <p><strong>Genres:</strong>
                    @foreach ($data->entertainmentGenerMappings as $mapping)
                        {{ $mapping->genredata->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
                
                <p><strong>Languages:</strong> {{ $data->language }}</p>
                
            </div>
            <div class="rating">
                <p><strong>IMDB Rating:</strong> {{ $data->IMDb_rating }}</p>
                <p><strong>Content Rating:</strong> {{ $data->content_rating }}</p>
            </div>
            @if ($data->type === 'tvshow')
            <div class="tvshow-details">
                <h2>TV Show Details</h2>
                <p><strong>Seasons:</strong> {{ $data->season->count() }}</p>
                <p><strong>Total Episodes:</strong> {{ $data->season->sum(function($season) { return $season->episodes->count(); }) }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="cast-crew">
    <div class="actors-directors">
        <div class="actors">
            <h3>Actors & Actress</h3>
            <div class="actor-list">
                @foreach ($data->entertainmentTalentMappings as $talentMapping)
                    @if ($talentMapping->talentprofile->type == 'actor')
                        <div class="actor">
                            <img src="{{ $talentMapping->talentprofile->file_url }}" alt="{{ $talentMapping->talentprofile->name }}">
                            <p>{{ $talentMapping->talentprofile->name }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="directors">
            <h3>Directors</h3>
            <div class="director-list">
                @foreach ($data->entertainmentTalentMappings as $talentMapping)
                    @if ($talentMapping->talentprofile->type == 'director')
                        <div class="director">
                            <img src="{{ $talentMapping->talentprofile->file_url }}" alt="{{ $talentMapping->talentprofile->name }}">
                            <p>{{ $talentMapping->talentprofile->name }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="reviews">
    <div class="review-summary">
        <div class="rating">
            @if ($totalReviews = count($data->entertainmentReviews))
                @php
                    $averageRating = $data->entertainmentReviews->avg('rating');
                @endphp
                <h4>{{ number_format($averageRating, 1) }}/5</h4>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $averageRating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <p>{{ $totalReviews }} reviews</p>
            @else
                <h4>0/5</h4>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        ☆
                    @endfor
                </div>
                <p>No reviews yet</p>
            @endif
        </div>
        <div class="rating-distribution">
            @php
                $ratingCounts = [
                    '5' => 0,
                    '4' => 0,
                    '3' => 0,
                    '2' => 0,
                    '1' => 0,
                ];
                foreach ($data->entertainmentReviews as $review) {
                    $ratingCounts[$review->rating]++;
                }
            @endphp
            @foreach ([5, 4, 3, 2, 1] as $rating)
                <div class="rating-bar">
                    <span class="rating-label">{{ $rating }}</span>
                    <div class="bar">
                        <div class="fill" style="width: {{ $totalReviews ? ($ratingCounts[$rating] / $totalReviews) * 100 : 0 }}%;"></div>
                    </div>
                    <span class="rating-count">{{ $ratingCounts[$rating] }} users</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="reviews">
    <h3>{{ count($data->entertainmentReviews) }} reviews for {{ $data->name }}</h3>
    @foreach ($data->entertainmentReviews as $review)
        <div class="review">
            <div class="reviewer">
                <img class="reviewer-profile-image" src="{{ $review->user->profile_image }}" alt="{{ $review->user->first_name }}">
                <div class="reviewer-info">
                    <h5>{{ $review->user->first_name }} {{ $review->user->last_name }}</h5>
                    <p><strong> <span class="star">★</span> {{ $review->rating }} rating</strong></p>
                    <p class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</p>
                </div>
            </div>
            <p>{{ $review->review }}</p>
        </div>
    @endforeach
</div>


<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .movie-details {
        display: flex;
    }

    .poster img {
        width: 300px;
        height: auto;
    }

    .details {
        margin-left: 20px;
    }

    .details h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .details p {
        margin-bottom: 10px;
    }

    .info,
    .movie-info,
    .rating {
        margin-bottom: 10px;
    }

    .cast-crew {
        margin-top: 20px;
    }

    .actor-list,
    .director-list {
        display: flex;
        flex-wrap: wrap;
    }

    .actor,
    .director {
        margin-right: 20px;
        margin-bottom: 20px;
        text-align: center;
    }

    .actor img,
    .director img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .reviews {
        margin-top: 20px;
    }

    .review-summary {
        display: flex;
        align-items: center;
    }

    .review-summary .rating {
        margin-right: 40px;
        text-align: center;
    }

    .review-summary .rating h4 {
        font-size: 2rem;
        margin-bottom: 5px;
    }

    .review-summary .rating .stars {
        font-size: 1.5rem;
        color: #f5c518;
    }
    .star{
        color: #f5c518;
    }

    .review-summary .rating p {
        margin-top: 5px;
        color: #888;
    }

    .rating-distribution {
        flex-grow: 1;
    }

    .rating-bar {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .rating-bar .rating-label {
        width: 20px;
        text-align: center;
    }

    .rating-bar .bar {
        flex-grow: 1;
        height: 10px;
        background-color: #ddd;
        margin: 0 10px;
        position: relative;
    }

    .rating-bar .bar .fill {
        height: 100%;
        background-color: #f5c518;
    }

    .rating-bar .rating-count {
        width: 60px;
        text-align: right;
    }
    .reviewer-profile-image {
        width: 50px; 
        height: 50px;  
        border-radius: 50%; 
        object-fit: cover; 
    }
    .reviewer {
        display: flex;
        align-items: center;
        padding: 10px 0;
    }
    .reviewer-info {
        margin-left: 10px;
    }
    .review p {
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .review-date {
        font-size: 0.9rem;
        color: #aaa;
    }
</style>
@endsection