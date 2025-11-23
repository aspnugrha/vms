<form class="w-100" action="{{ @$data->id ? route('master-visitor.update', @$data->id) : route('master-visitor.store') }}"
    enctype="multipart/form-data" method="POST" id="formData">
    @csrf
    <input type="hidden" name="_method" value="{{ @$data->id ? 'PUT' : 'POST' }}">
    <input type="hidden" name="id" value="{{ @$data->id ? $data->id : '' }}">

    <div class="form-group">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{ @$data ? $data->name : old('name') }}">
        <small id="nameHelp" class="invalid-feedback form-text text-danger">Please provide a valid informations.</small>
    </div>
    <div class="form-group">
        <label>Email <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="mdi mdi-email-outline"></i></span>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ @$data ? $data->email : old('email') }}">
            <small id="emailHelp" class="invalid-feedback form-text text-danger">Please provide a valid informations.</small>
        </div>
    </div>
    <div class="form-group">
        <label>Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter Phone Number" oninput="this.value=this.value.replace(/\D/g,'')" value="{{ @$data ? $data->phone_number : old('phone_number') }}">
            <small id="phone_numberHelp" class="invalid-feedback form-text text-danger">Please provide a valid informations.</small>
        </div>
    </div>
    <div class="form-group">
        <label for="image">Profile</label>
        <input type="file" class="form-control" name="image" id="image">
        <small id="imageHelp" class="invalid-feedback form-text text-danger">Please provide a valid informations.</small>

        @if (@$data && $data->image)
            <img src="{{ asset('assets/uploads/visitor/'.$data->image) }}" alt="Profile" class="mt-2" style="width: 100px;">
        @endif
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" name="active" id="active" {{ @$data && $data->active ? 'checked' : '' }}>
        <label class="form-check-label" for="active">
        Active
        </label>
    </div>
</form>