<div class="card card-profile">
    <div class="card-header" style="background-image: url('{{ asset('assets/backend') }}/img/blogpost.jpg')">
        <div class="profile-picture">
            <div class="avatar avatar-xl">
                <img src="{{ asset('assets'.($data->image ? '/uploads/visitor/'.$data->image : '/backend/img/profile.jpg')) }}" alt="..." class="avatar-img rounded-circle">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="user-profile text-center">
            <div class="name">{{ $data->name }}</div>
            <div class="job">{{ $data->code }}</div>
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
            <div class="d-flex justify-content-center w-100">
                <div class="w-50" id="qrcode"></div>
            </div>
            {{-- <div class="view-profile">
            <a href="#" class="btn btn-secondary w-100">View Full Profile</a>
            </div> --}}
        </div>
    </div>
    <div class="card-footer">
        <div class="row user-stats text-center">
            <div class="col-6">
                <div class="number">{{ $data->email }}</div>
                <div class="title">Email</div>
            </div>
            <div class="col-6">
                <div class="number">{{ $data->phone_number }}</div>
                <div class="title">Phone Number</div>
            </div>
            <div class="col-6">
                <div class="number">{{ $data->total_checkin }}</div>
                <div class="title">Total Check In</div>
            </div>
            <div class="col-6">
                <div class="number">{{ $data->total_checkout }}</div>
                <div class="title">Total Check Out</div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <p class="text-center">Data visit hari ini</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Type</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data_visit->count())
                        @foreach ($data_visit as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><h5 class="badge {{ $item->visit_type == 'IN' ? 'bg-success' : 'bg-danger' }} text-center">{{ $item->visit_type }}</h5></td>
                                <td>{{ $item->visit_type == 'IN' ? date('d F Y H:i:s', strtotime($item->checkin_time)) : '' }}</td>
                                <td>{{ $item->visit_type == 'OUT' ? date('d F Y H:i:s', strtotime($item->checkout_time)) : '' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">Tidak ada data</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<a class="btn btn-info w-100" href="{{ route('api.master-visitor.print-card', $data->code) }}" target="_blanl">Print Kartu Akses</a>

<script>
$(document).ready(function(){
    generateQR()
})
  function generateQR() {
    document.getElementById("qrcode").innerHTML = ""; // reset
    new QRCode(document.getElementById("qrcode"), {
      text: '{{ $data->code }}',
      width: 200,
      height: 200,
    });
  }
</script>