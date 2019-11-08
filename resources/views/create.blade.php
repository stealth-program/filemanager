@extends('layout')

@section('content')

    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="card uper">
        <div class="card-header">
            Add Student
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
            <form method="post" enctype="multipart/form-data" action="{{ route('store') }}">
                <div class="form-group">
                    @csrf
                    <label for="file">Input Name:</label>
                    <input type="file" class="form-control p-1" name="file" id="file"/>
                </div>
                <button type="submit" class="btn btn-primary">Upload file</button>
                <a href="{{route('index')}}" class="btn btn-secondary m-2 ml-4">Back</a>
            </form>
        </div>
    </div>
@endsection