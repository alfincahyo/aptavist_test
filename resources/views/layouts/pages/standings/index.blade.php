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
          <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              Add
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('matches.create-single') }}">Single Data</a></li>
              <li><a class="dropdown-item" href="{{ route('matches.create-multiple') }}">Multiple Data</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="dt" class="table table-bordered w-100">
        <thead>
          <tr>
            <th>No</th>
            <th>Club</th>
            <th>Match</th>
            <th>Win</th>
            <th>Draw</th>
            <th>Lose</th>
            <th>GM</th>
            <th>GK</th>
            <th>Points</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
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

    const dataTable = $('#dt').DataTable({
      info: false,
      paging: false,
      searching: false,
      ordering: false,
      ajax: {
        url: `{{ route('standings.index') }}`
      },
      columns: [{
          data: 'null',
          width: '50px',
          className: 'text-center',
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        }, {
          data: 'club',
          name: 'club',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'match_played',
          name: 'match_played',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'win',
          name: 'win',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'draw',
          name: 'draw',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'lose',
          name: 'lose',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'gm',
          name: 'gm',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'gk',
          name: 'gk',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'points',
          name: 'points',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
      ]
    });

  });
</script>
@endsection