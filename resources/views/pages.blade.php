<div class="container">
    <div class="row">
        @foreach ($data['data'] as $post)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if(isset($post['full_picture']))
                        <img src="{{ $post['full_picture'] }}" class="card-img-top" alt="Post Image">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">Posted on {{ \Carbon\Carbon::parse($post['created_time'])->format('F j, Y') }}</h5>
                        @if(isset($post['message']))
                            <p class="card-text">{{ $post['message'] }}</p>
                        @endif
                        @if(isset($post['story']))
                            <p class="card-text text-muted">{{ $post['story'] }}</p>
                        @endif
                        <a href="https://www.facebook.com/{{ $post['id'] }}" class="btn btn-primary" target="_blank">View Post</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
