@extends('layouts.admin')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
          <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
        </div>
        @include('backend.post_comments.filter.filter')
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Author</th>
                  <th width="40%">Comment</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th class="text-center" style="width: 30px">Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($comments as $comment)
                    <tr>
                        {{-- <td><img src="{{ get_gravatar($comment->email, 50) }}" class="img-circle"></td> --}}
                        <td><img src="{{asset('assets/users/default.jpeg')}}" alt=""class="img-circle"></td>
                        <td><a href="{!! $comment->url != '' ? $comment->url : 'javascript:void(0);' !!}" target="_blank">{{ $comment->name }}</a> {{ $comment->user_id != '' ? '(Member)' : '' }}</td>
                        <td>
                            {!!$comment->comment!!}
                            <div class="text-muted">
                                <a href="{{url('admin/post_comments_show/'.$comment->post_id)}}">{{$comment->post->title}}</a>
                            </div>
                        </td>
                        <td>{{ $comment->status() }}</td>
                        <td>{{$comment->created_at->format('d-m-Y h:i a')}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{url('admin/post_comments_edit/'.$comment->post_id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="if (confirm('Are you sure to delete this comment?')) {document.getElementById('comment-delete-{{$comment->id}}').submit();} else {return false;}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                <form action="{{url('admin/post_comments_destroy/'.$comment->id)}}" method="post" id="comment-delete-{{$comment->id}}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                  </tr>
                @empty
                    <td colspan="6" class="text-center">No Comments Found</td>
                @endforelse


              </tbody>
              <tfoot>
                <tr>
                 <th colspan="6">
                    <div class="float-right">
                        {!! $comments->appends(request()->input())->links() !!}
                    </div>
                 </th>
                </tr>
              </tfoot>
            </table>
          </div>

      </div>
@endsection
