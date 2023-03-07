@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <form id="formStore" action="{{ route('matches.post-single') }}" method="POST">
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
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('vendor/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/select2-bootstrap-5-theme.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('vendor/select2/select2.full.min.js')}}"></script>

<script>
  $(document).ready(function() {
    $('#select2HomeClub').select2({
      theme: "bootstrap-5",
      dropdownParent: $('#select2HomeClub').parent(),
      placeholder: 'Pilih Home Club',
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
      placeholder: 'Pilih Away Club',
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

  });
</script>
@endsection