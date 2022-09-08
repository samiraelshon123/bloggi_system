<div class="wn__sidebar">
    <!-- Start Single Widget -->

    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget recent_widget">
            <ul>

                <li class="list-group-item">
                    <img src="{{asset('assets/users/default.jpeg')}}" alt="{{auth()->user()->name}}">
                </li>
                <li class="list-group-item"><a href="{{url('user/dashboard')}}">My Posts</a></li>
                <li class="list-group-item"><a href="{{url('user/create_post')}}">Create Post</a></li>
                <li class="list-group-item"><a href="{{url('user/edit_info')}}">Update Information</a></li>
                <li class="list-group-item"><a href="{{url('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
        </div>
    </aside>
</div>
