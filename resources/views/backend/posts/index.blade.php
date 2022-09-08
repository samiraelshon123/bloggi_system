@extends('layouts.admin')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
          <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
          <div class="ml-auto">
            <a href="{{url('admin/posts_create')}}" class="btn btn-primary">
            <span class="icon-text-white-50">
                <i class="fa fa-plus"></i>
            </span>
            <span class="text">Add New Post</span>
        </a>
          </div>
        </div>
        @include('backend.posts.filter.filter')
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Comments</th>
                  <th>Status</th>
                  <th>Category</th>
                  <th>User</th>
                  <th>Created At</th>
                  <th class="text-center" style="width: 30px">Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td><a href="{{url('admin/post_show/'.$post->id)}}">{{$post->title}}</a></td>
                        <td>{!! $post->comment_able == 1 ? "<a href=\"" . url('admin/post_comments_index', ['post_id' => $post->id]) . "\">" . $post->comments->count() . "</a>" : 'Disallow' !!}</td>
                        <td>{{ $post->status() }}</td>
                        <td><a href="{{ url('admin/posts_index/'.$post->category_id) }}">{{ $post->category->name }}</a></td>
                        <td>{{$post->user->name}}</td>
                        <td>{{$post->created_at->format('d-m-Y h:i a')}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{url('admin/posts_edit/'.$post->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="if (confirm('Are you sure to delete this post?')) {document.getElementById('post-delete-{{$post->id}}').submit();} else {return false;}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                <form action="{{url('admin/posts_destroy/'.$post->id)}}" method="post" id="post-delete-{{$post->id}}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="7" class="text-center">No posts found</td>
                  </tr>
              @endforelse
              </tbody>
              <tfoot>
              <tr>
                  <th colspan="7">
                      <div class="float-right">
                        
                        {!! $posts->appends(request()->input())->links() !!}
                      </div>
                  </th>
              </tr>
              </tfoot>
          </table>
          </div>

      </div>
@endsection
