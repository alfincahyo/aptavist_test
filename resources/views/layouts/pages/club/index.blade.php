@extends('layouts.master')

@section('content')
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
        <h3 class="card-title">{{ $config['title'] }}</h3>
      </div>
      <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end align-items-baseline mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
          <a class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#storeModal"><i class="fa fa-plus fa-fw"></i> Tambah</a>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="dt" class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>City</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<form id="formStore" action="{{ route('clubs.store') }}" method="POST">
  @method('POST')
  @csrf
  <div class="modal fade" id="storeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Club</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="errorCreate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="control-label col-sm-3 align-self-center mb-0" for="name">Name :</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="name" name="name" placeholder="Insert Club name" value="">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="control-label col-sm-3 align-self-center mb-0" for="name">City :</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="city" name="city" placeholder="Insert City name" value="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <form id="formUpdate" action="" method="POST">
    @method('PUT')
    @csrf
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Club</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="errorUpdate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="control-label col-sm-3 align-self-center mb-0" for="name">Nama :</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="name" name="name" placeholder="Insert Club name" value="">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="control-label col-sm-3 align-self-center mb-0" for="name">City :</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="city" name="city" placeholder="Insert City name" value="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>

<div id="confirmDeleteModal" class="modal fade">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Item Confirm</h4>
        <button type="button" class="btn btn-outline-danger close" data-bs-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" />
        <div class="alert alert-warning">
          <strong>Warning !</strong> Are you sure to delete this item ?
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">No <i class="fa fa-fw fa-ban"></i></button>
          <button type="button" id="btnDelete" class="btn btn-success btn-sm">Yes <i class="fa fa-fw fa-check"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('vendor/datatables/datatables.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('vendor/datatables/datatables.min.js')}}"></script>
<script>
  $(document).ready(function() {
    let modalCreate = document.getElementById('storeModal');
    const bsCreate = new bootstrap.Modal(modalCreate);
    let modalEdit = document.getElementById('updateModal');
    const bsEdit = new bootstrap.Modal(modalEdit);


    const dataTable = $('#dt').DataTable({
      responsive: true,
      serverSide: true,
      processing: true,
      ajax: {
        url: `{{ route('clubs.index') }}`
      },
      columns: [{
          data: 'name',
          name: 'name'
        },
        {
          data: 'city',
          name: 'city'
        },
        {
          data: 'action',
          name: 'action',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
      ],
      rowCallback: function(row, data) {
        let api = this.api();
        $(row).find('.btn-delete').click(function() {
          var pk = $(this).data('id'),
            url = `{{ route("clubs.index") }}/` + pk;

          $('#confirmDeleteModal').modal('toggle');

          $('#btnDelete').click(function(e) {
            e.preventDefault();
            $.ajax({
              url: url,
              type: "DELETE",
              data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
              },
              error: function(response) {
                toastr.error(response, 'Failed !');
              },
              success: function(response) {
                if (response.status === "success") {
                  toastr.success(response.message, 'Success !');
                  $('#confirmDeleteModal').modal('hide');;
                  $('.modal-backdrop').remove();
                  api.draw();
                } else {
                  toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
                }
              }
            });
          });
        });
      }
    });

    modalCreate.addEventListener('hidden.bs.modal', function(event) {
      $('#formStore').find('input[type="text"], input[type="number"], select')
        .val('').trigger('change');
      $('#formStore').trigger('reset');
    });

    modalEdit.addEventListener('show.bs.modal', function(event) {
      let data = dataTable.row($(event.relatedTarget).closest('tr')).data();
      this.querySelector('#formUpdate').setAttribute('action', '{{ route("clubs.index") }}/' + data.id);
      this.querySelector('input[name=name]').value = data.name;
      this.querySelector('input[name=city]').value = data.city;
    });

    modalEdit.addEventListener('hidden.bs.modal', function(event) {
      $('#formUpdate').find('input[type="text"], input[type="number"], select')
        .val('').trigger('change');
      $('#formUpdate').trigger('reset');
      this.querySelector('#formUpdate').setAttribute('href', '');
    });

    $("#formStore").submit(function(e) {
      e.preventDefault();
      let form = $(this);
      let btnSubmit = form.find("[type='submit']");
      let btnSubmitHtml = btnSubmit.html();
      let url = form.attr("action");
      let data = new FormData(this);

      $.ajax({
        beforeSend: function() {
          btnSubmit.addClass("disabled").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop("disabled", "disabled");
        },
        cache: false,
        processData: false,
        contentType: false,
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
          let errorCreate = $('#errorCreate');
          errorCreate.css('display', 'none');
          errorCreate.find('.alert-text').html('');
          btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
          if (response.status === "success") {
            toastr.success(response.message, 'Success !');
            bsCreate.hide();
            dataTable.ajax.reload();
          } else {
            toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
            if (response.error !== undefined) {
              errorCreate.removeAttr('style');
              if (Array.isArray(response.error)) {
                $.each(response.error, function(key, value) {
                  errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              } else {
                errorCreate.find('.alert-text').append('<span style="display: block">' + response.message + '</span>');
              }
            }
          }
        },
        error: function(response) {
          btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
          toastr.error(response.responseJSON.message, 'Failed !');
        }
      });
    });

    $("#formUpdate").submit(function(e) {
      e.preventDefault();
      let form = $(this);
      let btnSubmit = form.find("[type='submit']");
      let btnSubmitHtml = btnSubmit.html();
      let url = form.attr("action");
      let data = new FormData(this);
      $.ajax({
        beforeSend: function() {
          btnSubmit.addClass("disabled").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop("disabled", "disabled");
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        processData: false,
        contentType: false,
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
          let errorEdit = $('#errorUpdate');
          errorEdit.css('display', 'none');
          errorEdit.find('.alert-text').html('');
          btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
          if (response.status === "success") {
            toastr.success(response.message, 'Success !');
            dataTable.draw();
            bsEdit.hide();
          } else {
            toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
            if (response.error !== undefined) {
              if (Array.isArray(response.error)) {
                errorEdit.removeAttr('style');
                $.each(response.error, function(key, value) {
                  errorEdit.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              } else {
                errorEdit.removeAttr('style');
                errorEdit.find('.alert-text').append('<span style="display: block">' + response.value + '</span>');
              }
            }
          }
        },
        error: function(response) {
          btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
          toastr.error(response.responseJSON.message, 'Failed !');
        }
      });
    });

  });
</script>
@endsection