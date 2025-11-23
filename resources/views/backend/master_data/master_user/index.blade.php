@extends('backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-filter"><i class="mdi mdi-filter-cog-outline"></i> Filter</button>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="$('#form-filter').submit()"><i class="mdi mdi-file-export-outline"></i> Export</button>
                    <a href="{{ route('master-user.create') }}" class="btn btn-dark btn-sm modal-show" title="Tambah User"><i class="mdi mdi-playlist-plus"></i> Tambah</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Active</th>
                                <th></th>
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
        <form action="{{ route('api.master-user.export') }}" method="POST" id="form-filter">
            @csrf
            <div class="form-group">
                <label>Dibuat tanggal</label>
                <input type="text" class="form-control" name="filter_created_at" id="filter_created_at">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="filter_active" id="filter_active" class="form-control">
                    <option value="">Pilih Status</option>
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
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

        var form = $('.modal-body #formData'),
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
                url: "{{ route('api.master-user.load-data') }}",
                type: "POST",
                // data: $('#form-filter').serialize(),
                data: function(d){
                    d.start= (d.start != null)? d.start : 0;
                    d.search= d.search;
                    d.created_at= $('#filter_created_at').val();
                    d.active= $('#filter_active').val();
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
                {data: "name", name: "name"},
                {data: "email", name: "email"},
                {data: "phone_number", name: "phone_number"},
                {
                    data: "id", 
                    name: "id",
                    render: function(data, type, row, meta) {
                        if (row.active == 1){
                            var btn = `<span class="badge bg-success rounded-pill">Active</span>`;
                        }else{
                            var btn = `<span class="badge bg-danger rounded-pill">Not Active</span>`;
                        }
						return btn;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        let url_detail = `{{ route('master-user.show', ':id') }}`;
                            url_detail = url_detail.replace(':id', row.id);
                        let url_edit = `{{ route('master-user.edit', ':id') }}`;
                            url_edit = url_edit.replace(':id', row.id);
                        let url_delete = `{{ route('master-user.destroy', ':id') }}`;
                            url_delete = url_delete.replace(':id', row.id);

                        var btn = `
                            <div class="btn-group">
                            <a href="${url_detail}" class="btn btn-sm btn-secondary p-2 modal-show detail" title="Detail User"><i class="mdi mdi-magnify"></i></a>
                        `

                        if(!['USR2000000000001', 'USR2000010100001'].includes(row.id)){
                            btn += `<a href="${url_edit}" class="btn btn-sm btn-info p-2 modal-show edit" title="Edit User"><i class="mdi mdi-lead-pencil"></i></a>`
                            btn += `<a href="${url_delete}" class="btn btn-sm btn-danger p-2 delete" title="Delete User"><i class="mdi mdi-trash-can"></i></a>`
                        }

                        btn += `</div>`

                        return btn;
                    },
                    // className: "text-center text-nowrap"
                }
            ]
        });
    }
</script>
@endsection