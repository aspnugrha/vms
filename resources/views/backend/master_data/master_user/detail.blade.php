<div class="card card-profile">
    <div class="card-header" style="background-image: url('{{ asset('assets/backend') }}/img/blogpost.jpg')">
        <div class="profile-picture">
            <div class="avatar avatar-xl">
                <img src="{{ asset('assets'.($data->image ? '/uploads/user/'.$data->image : '/backend/img/profile.jpg')) }}" alt="..." class="avatar-img rounded-circle">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="user-profile text-center">
            <div class="name">{{ $data->name }}</div>
            {{-- <div class="job">{{ $data->hasRole ? $data->hasRole->name : 'Unknown Role' }}</div> --}}
            <div class="desc">
                <span class="badge bg-{{ $data->active ? 'success' : 'danger' }}">{{ $data->active ? 'Active' : 'Not Active' }}</span>
            </div>
            <div class="social-media">
                <a class="btn btn-info btn-sm btn-link" href="#">
                    <span class="btn-label just-icon">
                        <i class="mdi mdi-email"></i>
                    </span>
                </a>
                <a class="btn btn-info btn-sm btn-link" href="#">
                    <span class="btn-label just-icon">
                        <i class="mdi mdi-phone"></i>
                    </span>
                </a>
            </div>
            {{-- <div class="view-profile">
            <a href="#" class="btn btn-secondary w-100">View Full Profile</a>
            </div> --}}
        </div>
    </div>
    <div class="card-footer">
        <div class="row user-stats text-center">
            <div class="col">
                <div class="number">{{ $data->email }}</div>
                <div class="title">Email</div>
            </div>
            <div class="col">
                <div class="number">{{ $data->phone_number }}</div>
                <div class="title">Phone Number</div>
            </div>
            {{-- <div class="col">
                <div class="number">{{ $data->hasRole ? $data->hasRole->name : 'Unknown Role' }}</div>
                <div class="title">Role</div>
            </div> --}}
        </div>
    </div>
</div>