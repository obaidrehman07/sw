<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Small World</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
        /* Custom CSS to increase button width */
        .btn-primary {
            width: 100%; /* Adjust the width as needed */
        }
    </style>
</head>
<body>

<div class="container mt-5">
  <h1 class="mb-4"><a href="/">Small World</a></h1>
  
  <form method="GET" action="{{ url('search-movies') }}">
  <div class="row">
      <div class="col-md-9">
          <div class="form-group">
            <input type="text" class="form-control" name="query" id="query" placeholder="Search Movies" required="required">
          </div>
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-primary">Search</button>
      </div>
  </div>
  </form>

  
  <div class="row">

    @foreach ($movies['results'] as $movie)
    <div class="col-md-3 mb-3" title="<?php echo $movie['title']; ?>">
      <div class="card">
        @if(isset($movie['poster_path']) && !empty($movie['poster_path']))
            <img src="https://image.tmdb.org/t/p/w138_and_h175_bestv2{{ $movie['poster_path'] }}" class="card-img-top" alt="{{ $movie['title'] }}">
        @else
            <img src="{{ asset('img/noimage.png') }}" class="card-img-top" alt="{{ $movie['title'] }}">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ strlen($movie['title']) > 19 ? substr($movie['title'], 0, 19) . '...' : $movie['title'] }}</h5>
          <h6 class="card-title">Rating: {{ number_format($movie['vote_average'], 1) }}</h6>
          <p class="card-text">{{ strlen($movie['overview']) > 90 ? substr($movie['overview'], 0, 90) . '...' : $movie['overview'] }}</p>
        </div>
      </div>
    </div>
    @endforeach

  </div>
  
  <!-- Pagination -->
  <ul class="pagination justify-content-center mt-4" id="pagination">

    <?php
    $movies = $movies['total_pages'];
    $itemsPerPage = 20;
    $current_page = $current_page_now;
    $totalPages = ceil($movies / $itemsPerPage);

    $minPage = max(1, $current_page - 5);
    $maxPage = min($totalPages, $current_page + 4);

    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . $add_query_string . '">Previous</a></li>';
    }
    for ($page = $minPage; $page <= $maxPage; $page++) {
        $isActive = $page === $current_page ? 'active' : '';
        echo '<li class="page-item '.$isActive.'"><a class="page-link" href="?page='.$page. $add_query_string . '">'.$page.'</a></li>';
    }
    if ($current_page < $totalPages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . $add_query_string . '">Next</a></li>';
    }
    ?>

  </ul>
</div>


</body>
</html>
