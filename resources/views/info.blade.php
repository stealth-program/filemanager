@extends('layout')

@section('content')

    <link rel="stylesheet" href="{{asset('css/infoStyle.css')}}">
    <div class="card uper">

        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            <br />
        @endif

        <div class="card-header">
            <h3 class="d-inline-flex">Information about file: {{$file->file_name}}</h3>
            <a href="{{route('index')}}" class="btn btn-secondary ml-4 float-right">Back</a>
        </div>
            @if(App\Helper::endswithArr($file->file_name, ['.jpeg', '.jpg', 'gif', 'png']))
                <div class="card-body">
                    <img src="{{Storage::url('public/' . $file->file_name)}}" alt="">
                </div>
                <br />
            @endif
            @if(App\Helper::endswithArr($file->file_name, ['.wav', '.mp3', 'ogg', 'webm', 'flac']))
                <div class="card-body">
                    <audio src="{{Storage::url('public/' . $file->file_name)}}" controls>
                </div>
                <br />
            @endif
        <div class="card-body">

            <pre>
                {{1 == dump($info)}}
            </pre>

        </div>

    </div>

    <div class="card mt-3">
        <div class="card-header">
            Comments
        </div>
        @foreach($comments as $comment)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$comment->author_name}}</h5>
                    <p class="card-text">{{$comment->comment_text}}</p>
                    <form
                            action="{{ route('comment.destroy', ['comment' => $comment->id, 'file' => $file->id])}}"
                            method="post" title="Delete">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="{{ route('comment.store', $file->id) }}">
                <div class="form-group">
                    @csrf
                    <label for="name">Name:</label>
                    <input type="text" class="form-control p-1" name="author_name" id="name"/>
                </div>
                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea type="text" class="form-control p-1" name="comment_text" id="comment"></textarea>
                    <input type="hidden" class="form-control p-1" name="file_id" id="file_id" value="{{$file->id}}"/>
                </div>
                <button type="submit" class="btn btn-primary">Add comment</button>
                <a href="{{route('index')}}" class="btn btn-secondary m-2 ml-4">Back</a>
            </form>
        </div>
    </div>
@endsection