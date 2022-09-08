@extends('layouts.admin')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
          <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
          <div class="ml-auto">
            <a href="{{url('admin/post_categories_create')}}" class="btn btn-primary">
            <span class="icon-text-white-50">
                <i class="fa fa-plus"></i>    
            </span>
            <span class="text">Add New Category</span>
        </a>
          </div>
        </div>
        @include('backend.posts_categories.filter.filter')
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Posts Count</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th class="text-center" style="width: 30px">Action</th>
                </tr>
              </thead>
              
              <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{$category->name}}</td>
                        <td><a href="{{url('admin/category_posts_index/'.$category->id)}}">{{$category->posts_count}}</a></td>
                        <td>{{ $category->status() }}</td>
                        <td>{{$category->created_at->format('d-m-Y h:i a')}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{url('admin/post_categories_edit/'.$category->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="if (confirm('Are you sure to delete this category?')) {document.getElementById('category-delete-{{$category->id}}').submit();} else {return false;}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                <form action="{{url('admin/post_categories_destroy/'.$category->id)}}" method="post" id="category-delete-{{$category->id}}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                  </tr>
                @empty
                    <td colspan="5" class="text-center">No Categories Found</td>
                @endforelse
                
                
              </tbody>
              <tfoot>
                <tr>
                 <th colspan="5">
                    <div class="float-right">
                        {!! $categories->appends(request()->input())->links() !!} 
                    </div>
                 </th>
                </tr>
              </tfoot>
            </table>
          </div>
        
      </div>
@endsection