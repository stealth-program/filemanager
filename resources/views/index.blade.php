@extends('layout')

@section('content')

    <link rel="stylesheet" href="{{asset('css/myStyle.css')}}">
    <div class="uper">
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            <br />
        @endif
            <div class="card m-2">
                <div class="row">
                    <a href="{{ route('create')}}" class="btn btn-primary m-2 ml-4 col-2">Add new file</a>
                    <div class="col-8 text-justify my-auto">
                        <h1 class="text-center my-auto">
                            <a href="{{route('index')}}">Files List</a>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="card m-2 p-1">
                <div class="container">
                    <div class="text-justify my-auto">
                        <form action="">
                            <div class="form-group mt-2">
                                <label for="search">
                                    <h2 class="text-left my-auto align-middle ml-1 mr-1 d-inline-block mb-0">Search</h2>
                                </label>
                                <input type="text"
                                       placeholder="File name..."
                                       name="search" id="search" class="form-control d-inline-block mb-0" style="max-width: 70%"
                                >
                                <button type="submit" class="btn btn-primary ml-2">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if(isset($_GET['search']))
                <div class="card m-2">
                    <div class="row m-1">
                        <div class="col-8 text-justify my-auto">
                            <h1 class="text-center my-auto">
                                Search results for: {{$_GET['search']}}
                            </h1>
                        </div>
                    </div>
                </div>
            @endif
            <table class="table table-striped">
            <thead>
            <tr>
                <td class="{{isset($_GET['orderBy']) && $_GET['orderBy'] == 'id' ? 'click' : ''}}">
                    <a href="{{route('index', array_merge(
                    $_GET,['orderBy' => 'id'],
                    isset($_GET['direction']) && $_GET['direction'] == 'asc' ? ['direction' => 'desc'] : ['direction' => 'asc']))}}"
                    >ID</a>
                </td>
                <td class="{{isset($_GET['orderBy']) && $_GET['orderBy'] == 'file_name' ? 'click' : ''}}">
                    <a href="{{route('index', array_merge(
                    $_GET,['orderBy' => 'file_name'],
                    isset($_GET['direction']) && $_GET['direction'] == 'asc' ? ['direction' => 'desc'] : ['direction' => 'asc']))}}"
                    >File name</a>
                </td>
                <td>Icon</td>
                <td class="{{isset($_GET['orderBy']) && $_GET['orderBy'] == 'file_size' ? 'click' : ''}}">
                    <a href="{{route('index', array_merge(
                    $_GET,['orderBy' => 'file_size'],
                    isset($_GET['direction']) && $_GET['direction'] == 'asc' ? ['direction' => 'desc'] : ['direction' => 'asc']))}}"
                    >File size</a>
                </td>
                <td class="{{isset($_GET['orderBy']) && $_GET['orderBy'] == 'created_at' ? 'click' : ''}}">
                    <a href="{{route('index', array_merge(
                    $_GET,['orderBy' => 'created_at'],
                    isset($_GET['direction']) && $_GET['direction'] == 'asc' ? ['direction' => 'desc'] : ['direction' => 'asc']))}}"
                    >Created at</a>
                </td>
                <td>Download link</td>
                <td colspan="2">Actions</td>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr title="Show additional information" data-href="{{route('show', $file->id)}}">
                    <td>{{$file->id}}</td>
                    <td>{{$file->file_name}}</td>
                    <td><img
                                src=
                                @if(Storage::disk('icons')->exists(($file->id) . '.jpg'))
                                {{asset(($file->id) . '.jpg')}}
                                @else {{ asset('assets/icon.png') }}
                                @endif
                                alt="" title="Icon"></td>
                    <td>{{$file->file_size}}</td>
                    <td>{{$file->created_at}}</td>
                    <td><a title="Download" href="{!! $file->download_link !!}">{!! $file->download_link !!}</a></td>
                    <td>
                        <form action="{{ route('destroy', $file->id)}}" method="post" title="Delete">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="bot">
            {{ $files->appends($_GET)->links() }}

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script>
        $('tr[data-href]').on("click", function() {
            document.location = $(this).data('href');
        });
    </script>
@endsection