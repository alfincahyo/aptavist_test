@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
  <form id="formStore" action="{{ route('matches.post-multiple') }}" method="POST">
    @method('POST')
    @csrf
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6 col-6 d-flex justify-content-center justify-content-md-start align-items-center mb-3 mb-md-0">
            <h3 class="card-title">{{ $config['title'] }}</h3>
          </div>
          <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-end align-items-baseline mb-md-0 mb-2">
            <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
              <button class="btn btn-outline-primary" type="submit"><i class="fa fa-plus fa-fw"></i> Save</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="errorCreate" class="mb-3" style="display:none;">
          <div class="alert alert-danger" role="alert">
            <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
            <div class="alert-text">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3 row">
              <label class="control-label col-sm-3 align-self-center mb-0" for="name">Home Club</label>
              <div class="col-sm-9">
                <select id="select2HomeClub" style="width: 100% !important;" name="home_club_id">
                  @isset($data->home_club)
                  <option value="{{ $data->home_club->id }}" selected="selected">{{ $data->home_club->name }}</option>
                  @endisset
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <label class="control-label col-sm-3 align-self-center mb-0" for="name">Home Goal</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" id="home_goal" name="home_goal" placeholder="Insert home club goal" value="{{ $data->home_goal ?? '' }}">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3 row">
              <label class="control-label col-sm-3 align-self-center mb-0" for="name">Away Club</label>
              <div class="col-sm-9">
                <select id="select2AwayClub" style="width: 100% !important;" name="away_club_id">
                  @isset($data->away_club)
                  <option value="{{ $data->away_club->id }}" selected="selected">{{ $data->away_club->name }}</option>
                  @endisset
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <label class="control-label col-sm-3 align-self-center mb-0" for="name">Away Goal</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" id="away_goal" name="away_goal" placeholder="Insert away club goal" value="{{ $data->away_goal ?? '' }}">
              </div>
            </div>
            <button id="add-match" class="btn btn-outline-primary mb-3 float-end" type="button"><i class="fa fa-plus fa-fw"></i> Tambah Match</button>
          </div>
        </div>

        <div class="table-responsive">
          <table id="dt" class="table table-bordered">
            <thead>
              <tr>
                <th>Home Club</th>
                <th>Home Goal</th>
                <th>Away Club</th>
                <th>Away Goal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('vendor/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/select2-bootstrap-5-theme.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('vendor/datatables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/select2/select2.full.min.js')}}"></script>

<script>
  $(document).ready(function() {


    $('#select2HomeClub').select2({
      theme: "bootstrap-5",
      dropdownParent: $('#select2HomeClub').parent(),
      placeholder: 'Choose Home Club',
      allowClear: true,
      ajax: {
        url: `{{ route('search.clubs') }}`,
        dataType: 'json',
        processResults: function(response) {
          let results = $.map(response.data, function(row, index) {
            row.id = row.id;
            row.text = row.name;
            return row;
          });
          return {
            results: results,
            pagination: {
              more: (response.next_page_url != null)
            }
          };
        }
      }
    });

    $('#select2AwayClub').select2({
      theme: "bootstrap-5",
      dropdownParent: $('#select2AwayClub').parent(),
      placeholder: 'Choose Away Club',
      allowClear: true,
      ajax: {
        url: `{{ route('search.clubs') }}`,
        dataType: 'json',
        processResults: function(response) {
          let results = $.map(response.data, function(row, index) {
            row.id = row.id;
            row.text = row.name;
            return row;
          });
          return {
            results: results,
            pagination: {
              more: (response.next_page_url != null)
            }
          };
        }
      }
    });

    const dataTable = $('#dt').DataTable({
      data: <?= isset($data->items) ? json_encode($data->items) : "[]"; ?>,
      info: false,
      paging: false,
      searching: false,
      ordering: false,
      columns: [{
          data: 'home_club_name',
          width: '180px',
          render: function(data, type, row, meta) {
            return `
                    <input class="form-control form-control-sm" value="${data}" readonly>
                    <input type="hidden" name="matches[${meta.row}][home_club_id]" class="form-control form-control-sm" value="${row.home_club_id}">
                    `;
          }
        },
        {
          data: 'home_goal',
          width: '180px',
          render: function(data, type, row, meta) {
            return `<input name="matches[${meta.row}][home_goal]" class="form-control form-control-sm text-right" value="${data}" readonly>`;
          }
        },
        {
          data: 'away_club_name',
          width: '180px',
          className: 'text-right',
          render: function(data, type, row, meta) {
            return `
                    <input class="form-control form-control-sm" value="${data}" readonly>
                    <input type="hidden" class="form-control form-control-sm" name="matches[${meta.row}][away_club_id]" value="${row.away_club_id}">
                    `;
          }
        },
        {
          data: 'away_goal',
          width: '180px',
          render: function(data, type, row, meta) {
            return `<input name="matches[${meta.row}][away_goal]" class="form-control form-control-sm text-right" value="${data}" readonly>`;
          }
        },
        {
          data: 'id',
          className: 'text-center',
          width: '50px',
          orderable: false,
          render: function(data, type, row, meta) {
            return `
                    <button type="button" class="btn btn-sm btn-light row-remove"><i class="fa fa-minus"></i></button>
                    `;
          }
        }
      ],
      createdRow: function(row, data, dataIndex, cells) {
        let api = this.api();
        $(row).find('.row-remove').click(function(e) {
          e.preventDefault();
          api.row(row).remove().draw();
        });
      },
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
            setTimeout(function() {
              if (response.redirect === "" || response.redirect === "reload") {
                location.reload();
              } else {
                location.href = response.redirect;
              }
            }, 1000);
          } else {
            toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
            if (response.error !== undefined) {
              errorCreate.removeAttr('style');
              $.each(response.error, function(key, value) {
                errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
              });
            }
          }
        },
        error: function(response) {
          btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
          toastr.error(response.responseJSON.message, 'Failed !');
        }
      });
    });

    $('#add-match').click(function(e) {
      let home_club_id = $('#select2HomeClub').val(),
        home_goal = $('#home_goal').val(),
        away_club_id = $('#select2AwayClub').val(),
        away_goal = $('#away_goal').val();
      console.log(home_club_id);
      if (home_club_id == null || home_goal == null || away_club_id == null || away_goal == null) {
        toastr.error('Fill all form', 'Failed !');
      } else if (home_club_id == away_club_id) {
        toastr.error('home team and away team cannot be the same', 'Failed !');
      } else {
        let data = {
          home_club_name: $('#select2HomeClub').select2('data')[0]['text'],
          home_club_id: $('#select2HomeClub').val(),
          home_goal: $('#home_goal').val(),
          away_club_name: $('#select2AwayClub').select2('data')[0]['text'],
          away_club_id: $('#select2AwayClub').val(),
          away_goal: $('#away_goal').val(),
        };
        dataTable.row.add(data).draw();
        $('#select2HomeClub').val(null).trigger('change');
        $('#home_goal').val('');
        $('#select2AwayClub').val(null).trigger('change');
        $('#away_goal').val('');
      }
    });

  });
</script>
@endsection