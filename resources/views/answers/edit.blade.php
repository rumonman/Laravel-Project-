@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                	<div class="d-flex align-items-center">
                		<h3>Edit Answers</h3>
                		<h3 class="ml-auto">
                			<a class="btn btn-outline-secondary btn-sm" href="{{route('questions.index')}}">
                				Back To All Questions
                			</a>
                		</h3>
                	</div>
                </div>
                
                <div class="card-body">
                   <form action="{{route('questions.answers.update', [$question->id, $answer->id])}}" method="post">
                  @method('PATCH')
                   	@csrf
    
                   	<div class="form-group">
                   		<label for="question_body">Update Your Answers</label>
                   		<textarea class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" id="question_body" rows="10">  
                      {{old('body',$answer->body)}} 
                    </textarea>
                   		@if ($errors->has('body'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span> 
                                @endif
                   	</div>
                     
                     <div class="form-group">
                   		<button type="submit" class="btn btn-outline-primary btn-sm">Update This Answers</button> 
                    	</div>

                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
