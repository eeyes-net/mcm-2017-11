@extends('index.layouts.master')

@section('main')
    <h1>公告</h1>
    <table class="table">
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td><a href="{{ action('Index\PostController@show', ['id' => $post->id]) }}">{{ $post->title }}</a></td>
                    <td>{{ $post->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links('vendor.pagination.bootstrap-4') }}
@stop
