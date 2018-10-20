<?php

// needs to be moved out of view

use App\MarvelAPI;

$client = new MarvelAPI();

$params = ['orderBy' => '-focDate'];
$resultsPerPage = 18;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$dataComics = $client->getComics($resultsPerPage, $page, $params);
?>

@extends('template')

@section('content')
    <div class="comics row" data-count={{ $dataComics->data->count }}>
        @if ($dataComics && $dataComics->data->results) 
            @foreach ($dataComics->data->results as $comic)
                <div class="comic card col-sm-2">
                  <img class="comic-card-img card-img-top" src="{{ $comic->thumbnail->path }}/portrait_incredible.{{ $comic->thumbnail->extension }}" alt="">
                  <div class="card-body">
                    <h3>{{$comic->title}}</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#comic-model-{{ $comic->id }}">Info</button>
                  </div>
                  <div id="comic-model-{{ $comic->id }}" class="comic-model modal" tabindex="-1" role="dialog">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-5">
                                <img src="{{ $comic->thumbnail->path }}/portrait_incredible.{{ $comic->thumbnail->extension }}" alt="">
                              </div>
                              <div class="col-7">
                                <h3>{{ $comic->title }}</h3>
                                @if ($comic->format)
                                <h4>Format: {{ $comic->format }}</h4>
                                @endif
                                <h4>Description</h4>
                                {!! $comic->description ?? "<p>No information available</p>" !!}
                                @if ($comic->isbn)
                                <h5>ISBN: {{ $comic->isbn }}</h5>
                                @endif
                                @if ($comic->upc)
                                <h5>UPC: {{ $comic->upc }}</h5>
                                @endif
                                
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12">
                                <h4>Creators</h4>
                                <div class="ul">
                                  @foreach ($comic->creators->items as $creator)
                                    <li class="list-group-item">{{ $creator->name }} - {{ ucwords($creator->role) }}</li>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            @endforeach 
        @else
            <div class="error">Error fetching comics...</div>
            <?php print_r($comics); ?>
        @endif
    </div>
    <br>
    <nav aria-label="page-control">
      <ul class="pagination pagination-lg justify-content-center">
        @if ($page > 1)
          <li class="page-item"><a class="page-link" href="comics?page={{ ($page - 1) }}">Previous Page</a></li>
          <li class="page-item"><a class="page-link" href="comics">Reset</a></li>
        @endif
        <li class="page-item"><a class="page-link" href="comics?page={{ ($page + 1) }}">Next Page</a></li>
      </ul>
    </nav>
    
@endsection