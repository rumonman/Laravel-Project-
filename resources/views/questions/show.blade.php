@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                    <div class="d-flex align-items-center">
                        <h3>{{$question->title}}</h3>
                        <h3 class="ml-auto">
                            <a class="btn btn-outline-secondary btn-sm" href="{{route('questions.index')}}">
                                Back To All Questions
                            </a>
                        </h3>
                    </div>
                </div>
                <hr>
                <div class="media">
                    <div class="d-flex flex-column votes-control">
                        <a class="vote-up  
                        {{Auth::guest() ? 'off' : ''}}"
                        onclick="event.preventDefault(); document.getElementById('questions-vote-up-{{$question->id}}').submit()">
                        <i class="fas fa-caret-up fa-2x"></i>
                    </a>
                    <span class="votes-count">
                     {{$question->votes_count}}
                    </span>

                    <form action="/questions/{{$question->id}}/vote" id="questions-vote-up-{{$question->id}}" style="display: none" method="post">
                        @csrf
                      <input type="hidden" name="vote" value="1">
                    </form>
                    <a class="vote-down {{Auth::guest() ? 'off' : ''}}"
                    onclick="event.preventDefault(); document.getElementById('questions-vote-down-{{$question->id}}').submit()">
                    <i class="fas fa-caret-down fa-2x"></i>
                 </a>
                 <form action="/questions/{{$question->id}}/vote" id="questions-vote-down-{{$question->id}}" style="display: none" method="post">
                        @csrf
                      <input type="hidden" name="vote" value="-1">
                    </form>

                 <a class="favorite mt-3 {{Auth::guest()?'off':($question->is_favorited) ? 'fabs':''}}"
                   onclick="event.preventDefault(); document.getElementById('questions-favorites-{{$question->id}}').submit()" >
                    <i class="fas fa-star fa-2x"></i>
                    <span class="favorite">
                        {{$question->favorites_count}}
                    </span>
                 </a>
                 <form action="/questions/{{$question->id}}/favorites" id="questions-favorites-{{$question->id}}" style="display: none" method="post">
                        @csrf

                        @if($question->is_favorited)
                             @method('DELETE')
                        @endif
                    </form>
                    </div>
                   <div class="media-body">
                       {!! $question->body_html !!}

                   <div class="float-right">
                             <span class="text-muted">
                                Questioned {{$question->create_date}}
                             </span>
                             <div class="media">
                                 <a class="pr-2" href="{{$question->user->url}}">
                                 <img src="{{$question->user->avatar}}">
                                  </a>
                                  <div class="media-body">
                                    <a href="{{$question->user->url}}">
                                      {{$question->user->name}}
                                  </a>  
                                  </div>
                             </div>
                         </div>
                   </div>

                </div>
                </div>
            </div>
        </div>
    </div>

<!................Answers.......................>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2>Your Answers</h2>
                @include('layouts._message')
                <form action="{{route('questions.answers.store', $question->id)}}" method="post">
                    @csrf
                    <div class="form-group">
                      <textarea class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" rows="7"></textarea>
                                @if ($errors->has('body'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span> 
                                @endif  
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-sm btn-outline-secondary">Submit</button>   
                    </div>
                </form>
                <hr>
                <div class="card-title">
                    <h3>{{$question->answers_count}}
                       {{str_plural('Answere',$question->answers_count)}}
                    </h3>
                </div>
                <hr>
                @foreach($question->answers as $answer)
                 <div class="media">

                    <div class="d-flex flex-column votes-control">
                        

                            <a class="vote-up  
                               {{Auth::guest() ? 'off' : ''}}"  
                               
                                onclick="event.preventDefault(); document.getElementById('answers-vote-up{{$answer->id}}').submit()">
                                <i class="fas fa-caret-up fa-3x"></i>
                            </a>
                            <span class="votes-count">
                                {{$answer->votes_count}}
                            </span>
                             <form action="/answers/{{$answer->id}}/vote" id="answers-vote-up{{$answer->id}}" style="display: none" method="post">
                                    @csrf
                                    <input type="hidden" name="vote" value="1">
                             </form>
                            <a class="vote-down  
                               {{Auth::guest() ? 'off' : '' }}" 
                               onclick="event.preventDefault(); document.getElementById('answers-vote-down{{$answer->id}}').submit()">
                                <i class="fas fa-caret-down fa-3x"></i>
                            </a>
                            <form action="/answers/{{$answer->id}}/vote" id="answers-vote-down{{$answer->id}}" style="display: none" method="post">
                                    @csrf
                                    <input type="hidden" name="vote" value="-1">
                             </form>



                 @can('accept',$answer)
                 

                    <form action="{{route('accept.answer',$answer->id)}}" id="accept_answer {{$answer->id}}" style="display: none" method="post">
                        @csrf
                    </form>
                    @else

                    @if($answer->is_best)
                     <a title="This Is Best Answers" class="favorite mt-3 {{$answer->status}}">
                    <i class="fas fa-check fa-1x"></i>

                     </a>
                    @endif

                    @endcan
                    </div>

                     <div class="media-body">
                         {!! $answer->body_html!!}
                         <div class="row">
                            <div class="col-md-4">
                                <div class="ml-auto">
                            @can('update', $answer)
                            <a href="{{route('questions.answers.edit',[$question->id,$answer->id])}}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endcan
                            
                            @can('delete', $answer)
                            <form class="form-delete" action="{{route('questions.answers.destroy', [$question->id,$answer->id])}}" 
                                method="post">
                                @method('DELETE')
                                @csrf
                                <button onclick="return confirm('are You Sure')" type="submit" class="btn btn-sm btn-outline-secondary">
                                    Delete
                                </button>
                            </form>
                               @endcan
                               </div>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="col-md-4">
                                <div class="float-right">
                             <span class="text-muted">
                                Answered {{$answer->create_date}}
                             </span>
                             <div class="media">
                                 <a class="pr-2" href="{{$answer->user->url}}">
                                 <img src="{{$answer->user->avatar}}">
                                  </a>
                                  <div class="media-body">
                                    <a href="{{$answer->user->url}}">
                                      {{$answer->user->name}}
                                  </a>  
                                  </div>
                             </div>
                         </div>
                            </div>
                             
                         </div>
                         
                     </div>
                 </div>
                @endforeach
            </div>
        </div>
    </div>
    
</div>
</div>
@endsection
