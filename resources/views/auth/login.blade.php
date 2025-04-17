<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
      background-color: #f5f0f8; /* Soft purple background */
    }
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-purple {
      background-color: #8e24aa; /* Medium purple */
        border: none;
    }
    .btn-purple:hover {
      background-color: #7b1fa2;
    }
    .text-purple {
      color: #6a1b9a; /* Darker purple untuk judul */
    }
    a.custom-link {
      color: #8e24aa;
      text-decoration: none;
    }
    a.custom-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4" style="max-width: 400px; width:100%;">
      <div class="card-body">
        <h2 class="card-title text-center mb-4 text-purple">Login</h2>
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required autofocus class="form-control">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required class="form-control">
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input type="checkbox" name="remember" id="remember" class="form-check-input">
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
          </div>
          <button type="submit" class="btn btn-purple w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
