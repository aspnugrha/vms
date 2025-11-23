@extends('backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 col-md-4">
        <div class="card w-100 p-3">
            <div class="d-flex justify-content-between align-content-center">
                <div class="align-content-center px-2">
                    <i class="mdi mdi-account-group text-dark" style="font-size: 40px;"></i>
                </div>
                <div class="w-100 px-4">
                    <h4 id="text-total-visitor"></h4>
                    <span class="text-muted">Total Visitor</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card w-100 p-3">
            <div class="d-flex justify-content-between align-content-center">
                <div class="align-content-center px-2">
                    <i class="mdi mdi-account-check text-info" style="font-size: 40px;"></i>
                </div>
                <div class="w-100 px-4">
                    <h4 id="text-total-visitor-in"></h4>
                    <span class="text-muted">Total Visitor IN</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card w-100 p-3">
            <div class="d-flex justify-content-between align-content-center">
                <div class="align-content-center px-2">
                    <i class="mdi mdi-account-minus text-danger" style="font-size: 40px;"></i>
                </div>
                <div class="w-100 px-4">
                    <h4 id="text-total-visitor-out"></h4>
                    <span class="text-muted">Total Visitor OUT</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            {{-- <div class="card-header">
                <h3 class="mb-4">Simple Line Chart - Chart.js</h3>
            </div> --}}
            <div class="card-body">
                <canvas id="myChart" height="170"></canvas>
        
                {{-- <button class="btn btn-primary mt-3" id="updateBtn">Update Data</button> --}}
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Visitor</th>
                                <th>Type</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Visit Time</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-newest-visitors">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let count = 0;
    $(document).ready(function(){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});	
        loadData();

        let interval = setInterval(() => {
            loadData()

            count++;

            if (count >= 50) {
                clearInterval(interval);   // stop interval
                location.reload();         // reload page
            }

        }, 5000);
    })

    // =========================
    // DEFAULT DATA
    // =========================
    let labels = ["Jam 7", "Jam 8", "Jam 9", "Jam 10", "Jam 11", "Jam 12"];
    let dataValues = [0, 0, 0 ,0, 0 ,0];

    // =========================
    // INIT CHART
    // =========================
    const ctx = document.getElementById('myChart').getContext('2d');

    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Visitor",
                data: dataValues,
                borderWidth: 2,
                borderColor: "blue",
                tension: 0.3
            }]
        },
        options: {
            responsive: true
        }
    });

    // =========================
    // UPDATE CHART ON BUTTON CLICK
    // =========================
    // document.getElementById("updateBtn").addEventListener("click", () => {

    //     // Generate random data for demo
    //     let newData = dataValues.map(() => Math.floor(Math.random() * 50) + 1);

    //     myChart.data.datasets[0].data = newData;
    //     myChart.update();
    // });

    function loadData(){
        $.ajax({
            url: "{{ route('api.dashboard.load-data') }}",
            method: 'POST',
            // data: ,
            success: function(res) {
                console.log(res);

                $('#text-total-visitor').text(res.total_visitor.toLocaleString('id-ID'))
                $('#text-total-visitor-in').text(res.total_visitor_in.toLocaleString('id-ID'))
                $('#text-total-visitor-out').text(res.total_visitor_out.toLocaleString('id-ID'))

                var newest_visitors = ``

                res.newest_visitors.forEach((item, index) => {
                    
                    newest_visitors += `
                    <tr>
                        <td>${index +1}</td>
                        <td>
                            <h5 class="m-0 p-0">${item.visitor_name}</h5>
                            <span class="text-muted">${item.visitor_email} | ${item.visitor_phone_number}</span>
                        </td>
                        <td><span class="badge bg-${item.visit_type == 'IN' ? 'success' : 'danger'} rounded-pill">${item.visit_type}</span></td>
                        <td>${(item.visit_type == 'IN' ? convertDate(item.checkin_time) : '')}</td>
                        <td>${(item.visit_type == 'OUT' ? convertDate(item.checkout_time) : '')}</td>
                        <td>${(item.visit_type == 'OUT' ? item.visit_time_total+' detik' : '')}</td>
                    </tr>
                    `
                });

                $('#tbody-newest-visitors').html(newest_visitors)

                myChart.data.labels = res.chart_label;
                myChart.data.datasets[0].data = res.chart_data;

                myChart.update();
            },
            error: function(xhr) {
                console.log('error', xhr);
                
            },
        });
    }

    function convertDate(date){
        return new Date(date).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit" });
    }
</script>
@endsection