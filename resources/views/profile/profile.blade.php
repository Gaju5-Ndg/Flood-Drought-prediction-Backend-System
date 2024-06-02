@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="content">
        <div class="bg-white border rounded">
            <div class="row no-gutters">
                <div class="col-lg-6">
                    <div class="profile-content-left profile-left-spacing pt-5 pb-3 px-3 px-xl-5">
                        <div class="card text-center widget-profile px-0 border-0">
                            <div class="card-img mx-auto mt-4" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%;">
                                <img class="img-fluid" src="/avatars/{{ Auth::user()->avatar }}" alt="profile picture" style="object-fit: cover; width: 100%; height: 100%;">
                            </div>
                            <div class="card-body">
                                <h4 class="py-2 text-dark">{{ Auth::user()->name }}</h4>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="badge bg-dark">{{ $v }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-content-right profile-right-spacing pt-5 pb-3 px-3 px-xl-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8"> <!-- Adjust column width as needed -->
                                <div class="card-body">
                                    <h4 class="py-2 text-dark">Update Profile Picture</h4>
                                    <form action="{{ route('profile.update',['user' => $user]) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group ">
                                            <input type="file" class="form-control-file" name="avatar" id="avatar">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Content -->
</div> <!-- End Content Wrapper -->
@endsection
