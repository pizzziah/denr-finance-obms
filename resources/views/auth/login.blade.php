<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700;800&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  <div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="d-flex flex-column align-items-center lh-1 gap-0 p-0" style="color:var(--primary);">
      <img  src="{{ asset('images/DENR-logo.png') }}" 
          style="width: 15%;">
      <h3 class="text-center mt-4 fw-bold">Obligation Disbursement Monitoring System </h3>
      <h4 class="text-center mb-4"><i>DENR CAR Finance Office</i></h4>
    </div>

    <div class="card shadow" style="width: 450px">
      <div class="card-body p-4">
        <form method="POST" action="{{ route('login.submit') }}">
        @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control p-2" placeholder="juandelacruz@denr.gov.ph" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control p-2" placeholder="********" required>
            </div>

            <button type="submit"
                    class="btn w-100 p-2"
                    style="background-color: var(--primary); color: var(--background);">
                Log In
            </button>
        </form>
        </div>
    </div>
  </div>

  <footer class="position-fixed bottom-0 end-0 p-4">
      @include('layouts.footer')
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>