@extends('layouts.app')
@section('content')
    <!-- Start Blog Area -->
    
                <div class="col-lg-9 col-12">
                   <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Comments</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr>
                                        <td>{{$post->title}}</td>
                                        <td>{{$post->comments_count}}</td>
                                        <td>{{$post->status}}</td>
                                        <td>
                                            <a href="{{url('user/edit_post/'.$post->id)}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            <a href="#" onclick="if (confirm('Are you sure to delete this post?')) {document.getElementById('post-delete-{{$post->id}}').submit();} else {return false;}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                            <form action="{{url('user/delete_post/'.$post->id)}}" method="post" id="post-delete-{{$post->id}}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No Posts Found</td>
                                    </tr>
                                @endforelse

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">{{$posts->links()}}</td>
                                </tr>
                            </tfoot>
                        </table>
                   </div>

                </div>
                <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                    @include('partial.frontend.users.sidebar')
                </div>

    <!-- End Blog Area -->
@endsection
