@extends('backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-filter"><i class="mdi mdi-filter-cog-outline"></i> Filter</button>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="$('#form-filter').submit()"><i class="mdi mdi-file-export-outline"></i> Export</button>
                </div>
            </div>
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
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size: 15px;">Filter Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('api.guestbook.export') }}" method="POST" id="form-filter">
            @csrf
            <div class="form-group">
                <label>Visitor</label>
                <select name="filter_visitor[]" id="filter_visitor" class="form-control w-100" multiple>
                    <option value="">Pilih Visitor</option>
                    @foreach ($visitors as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control" name="filter_created_at" id="filter_created_at">
            </div>
            <div class="form-group">
                <label>Tanggal Check In</label>
                <input type="text" class="form-control" name="filter_checkin" id="filter_checkin">
            </div>
            <div class="form-group">
                <label>Tanggal Check Out</label>
                <input type="text" class="form-control" name="filter_checkout" id="filter_checkout">
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="filter_type" id="filter_type" class="form-control">
                    <option value="">Pilih Type</option>
                    <option value="IN">Check In</option>
                    <option value="OUT">Check Out</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-dark btn-sm" id="btn-apply-filter" onclick="loadData();$('#modal-filter').modal('hide')">Apply</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
    let table = '';
    $(document).ready(function(){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});	
        loadData()

        $('#filter_visitor').select2({
            dropdownParent: $('#form-filter')
        });

        $('#filter_created_at').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
  
        $('#filter_created_at').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
  
        $('#filter_created_at').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#filter_checkin').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
  
        $('#filter_checkin').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
  
        $('#filter_checkin').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#filter_checkout').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
  
        $('#filter_checkout').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
  
        $('#filter_checkout').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    })

    $('.card').on('click','.modal-show',function(event){
        event.preventDefault();

        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title');

        $('#modal-default-title').text(title);
        $('#modal-default-btn-save').removeClass('hidden').removeClass('d-none')
        .html('<i class="mdi mdi-content-save-check-outline"></i> '+(me.hasClass('edit') ? 'Update' : 'Create'));
        if(me.hasClass('detail') || me.hasClass('delete')) $('#modal-default-btn-save').addClass('d-none');

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(response){
                $('#modal-default-body').html(response);
            }
        });

        $('#modal-default').modal('show');
    });

    $('.modal-btn-save').on('click', function(event) {
        event.preventDefault();

        $('.custom-loader-overlay').css('display', 'flex')

        var form = $('.modal-body form'),
            url = form.attr('action');
        // Clear Validation
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.form-group').removeClass('has-error');

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(document.getElementById("formData")),
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    form.trigger('reset');
                    $('#modal-default').modal('hide');

                    showToastr('toast-top-right', 'success', "Data berhasil disimpan")

                    table.ajax.reload(null, false);
                } else {
                    showToastr('toast-top-right', 'error', "Terjadi kesalahan, silahkan ulangi kembali")
                }
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    console.log(res.errors);
                    
                    $.each(res.errors, function(key, value) {
                        $('#' + key)
                            // .closest('#error')
                            .addClass('is-invalid');
                        $('#' + key + 'Help').text(value.join(', '))
                    });
                }
                    
                showToastr('toast-top-right', 'error', "Please check the form for errors")
            },
            complete:function(){
                $('.custom-loader-overlay').css('display', 'none');
            }
        });
    });

    $('.card').on('click', '.delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var title = $(this).attr('title');
        
        // Confirm dialog based on action type
        var confirmText = title.includes('Delete') ? 
            'Are you sure you want to delete this data?' : 
            'Are you sure you want to restore this data?';
        
        
        var confirmButtonText = title.includes('Delete') ? 'Yes, delete it!' : 'Yes, restore it!';
        var confirmButtonColor = title.includes('Delete') ? '#dc3545' : '#ffc107';

        console.log(url, title);
        
        
        Swal.fire({
            title: 'Confirmation',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        console.log('delete', response);
                        
                        if(response.success) {
                            // Show success toast notification
                            toastr.success(
                                title.includes('Delete') ? 
                                'Data has been delete successfully!' : 
                                'Data has been restored successfully!',
                                'Success!'
                            );
                            
                            // Reload only the current page data
                            table.ajax.reload(null, false);
                        } else {
                            toastr.error(
                                'Failed to process your request',
                                'Error!'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.log('error', xhr);
                        
                        toastr.error(
                            'An error occurred while processing your request',
                            'Error!'
                        );
                    }
                });
            }
        });
    });

    function loadData(){
        table = $('#table-data').DataTable({
            destroy: true,
            responsive: true,
            processing: true,
            serverSide: true,
            stateSave: true,
            start: 0,
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('api.guestbook.load-data') }}",
                type: "POST",
                // data: $('#form-filter').serialize(),
                data: function(d){
                    d.start= (d.start != null)? d.start : 0;
                    d.search= d.search;
                    d.visitors= $('#filter_visitor').val();
                    d.created_at= $('#filter_created_at').val();
                    d.checkin_time= $('#filter_checkin').val();
                    d.checkout_time= $('#filter_checkout').val();
                    d.type= $('#filter_type').val();
                    // d.search= $('#searchValue').val();
                },
                statusCode: {
                    401:function() {
                        location.reload();
                        let timerInterval
                            Swal.fire({
                            title: 'Silahkan Login Kembali!',
                            html: 'Session telah habis<b></b>.',
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                                const b = Swal.getHtmlContainer().querySelector('b')
                                timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft()
                                }, 100)
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                            }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log('I was closed by the timer')
                            }
                        })
                    },
                },
                "error": function(xhr, error, thrown) {
                    console.log("Error occurred!");
                },
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {
                    data: "id", 
                    name: "id",
                    render: function(data, type, row, meta) {
                        var visitor = `
                        <h5 class="p-0 m-0">${row.visitor_name}</h5>
                        <span class="text-muted">${row.visitor_email} | ${row.visitor_phone_number}</span>
                        `
						return visitor;
                    }
                },
                {
                    data: "id", 
                    name: "id",
                    render: function(data, type, row, meta) {
						return `<span class="badge bg-${row.visit_type == 'IN' ? 'success' : 'danger'} rounded-pill">${row.visit_type}</span>`;
                    }
                },
                {data: "checkin_time", name: "checkin_time"},
                {data: "checkout_time", name: "checkout_time"},
                {
                    data: "id", 
                    name: "id",
                    render: function(data, type, row, meta) {
                        return row.visit_type == 'OUT' ? row.visit_time_total+' detik' : ''
                    }
                },
                // {
                //     data: "id", 
                //     name: "id",
                //     render: function(data, type, row, meta) {
                //         if (row.active == 1){
                //             var btn = `<span class="badge bg-success rounded-pill">Active</span>`;
                //         }else{
                //             var btn = `<span class="badge bg-danger rounded-pill">Not Active</span>`;
                //         }
				// 		return btn;
                //     }
                // },
                // {
                //     data: null,
                //     orderable: false,
                //     searchable: false,
                //     render: function(data, type, row, meta) {
                //         let url_detail = `{{ route('guestbook.show', ':id') }}`;
                //             url_detail = url_detail.replace(':id', row.id);
                //         let url_delete = `{{ route('guestbook.destroy', ':id') }}`;
                //             url_delete = url_delete.replace(':id', row.id);

                //         var btn = `
                //             <div class="btn-group">
                //             <a href="${url_detail}" class="btn btn-sm btn-secondary p-2 modal-show detail" title="Detail Visitor"><i class="mdi mdi-magnify"></i></a>
                //             <a href="${url_delete}" class="btn btn-sm btn-danger p-2 delete" title="Delete Visitor"><i class="mdi mdi-trash-can"></i></a>
                //             </div>
                //             `

                //         return btn;
                //     },
                //     // className: "text-center text-nowrap"
                // }
            ]
        });
    }
</script>
@endsection