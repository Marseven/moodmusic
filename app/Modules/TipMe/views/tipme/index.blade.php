<!DOCTYPE html>
<html>
<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
<style>
    body {
        background: black;
    }
    .card-header {
    }
    .card {
    }
    button {
        background: #F0D68B !important;
        border-color: #F0D68B !important;
        color: black !important;
    }
</style>
<body>
<div class="d-flex justify-content-center align-content-center mt-5">
    <img src="https://tipmeglobal.com/corp/wp-content/uploads/2020/09/logoN.png" alt="TipMe Global Services" id="logo" width="200px">
</div>
<div class='container d-flex justify-content-center align-items-center mt-5'>
    <div class="card" style='min-width: 350px; max-width: 400px'>
        <div class="card-body">
            @if (session('status') && session('status') == 'success')
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @elseif (session('status') && session('status') == 'failed')
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            <form method="post" action="{{ $formUrl }}">
                @csrf
                <div class="form-group">
                    <label>Enter your CID (Customer ID)</label>
                    <input type="text" name="number" class="form-control" id="form-group" placeholder="ex: CID100001" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>