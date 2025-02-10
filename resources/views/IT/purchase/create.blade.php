@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-1">Add Resort Activity</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action=" " method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="customerName">Activty Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Name">
                        </select>
                    </div>

                    <div class="form-row align-items-center mb-3">
                        
                        <input type="text" name="type" value="none" hidden>
                        
                        <div class="col-md-6">
                            <label for="Minimum">Minimum Time</label>
                            <input type="number" name="minimum" class="form-control" placeholder="Enter Minutes">
                         </div>

                        <div class="col-md-6">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">

                                <option value="1">Active</option>
                                <option value="0">In-Active</option>

                            </select>
                        </div>
                    </div>

                    <div class="form-row align-items-center mb-3">
                        <div class="col-md-6">
                            <label for="rate">Rate</label>
                            <input type="number" name="rate" class="form-control" placeholder="Enter Rate">
                        </div>
                        <div class="col-md-6">
                            <label for="points">Points</label>
                            <input type="number" name="points" class="form-control" placeholder="Points">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-4">Save Activity</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Custom JavaScript can be added here
</script>
@stop
