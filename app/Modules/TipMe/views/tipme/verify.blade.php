<!DOCTYPE html>
<html>
<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
<style>
    body {
        background: black;
    }
</style>
<body>
<div class="d-flex justify-content-center align-content-center mt-5">
    <img src="https://tipmeglobal.com/corp/wp-content/uploads/2020/09/logoN.png" alt="TipMe Global Services" id="logo" width="200px">
</div>
<div class='container d-flex justify-content-center align-items-center mt-5'>
    <div class="card" style='min-width: 350px; max-width: 400px'>
        <div class="card-body">
            @if(isset($status))
                @if ($status == 'success')
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @elseif ($status == 'failed')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
            @endif
            <form method="post" action="">
                @csrf
                <div class="form-group">
                    <div class="form-group">
                        <label>Enter OTP which sent to your phone.</label>
                        <input type="text" name="otp" class="form-control" id="form-group" required>
                        <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-block">Confirm</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>