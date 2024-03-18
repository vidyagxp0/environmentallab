<div class="account-box">
    <div class="account-wrapper">
        <h3 class="account-title">Reset Password</h3>
        <p class="account-subtitle">Input your email to register reset new password.</p>
        <!-- Account Form -->
        <form method="POST" action="/reset-password">
            @csrf
            <input type="hidden" name="mailId" value="{{ $email }}">
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label><strong>Repeat Password</strong></label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Choose Repeat Password">
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
            </div>
      
        </form>
        <!-- /Account Form -->
    </div>
</div>