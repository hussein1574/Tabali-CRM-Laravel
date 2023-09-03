<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tabali CRM - @yield('title')</title>
  <link href="{{url('/')}}/fonts/stylesheet.css" rel="stylesheet" />
  <link href="{{url('/')}}/css/style.css" rel="stylesheet" />
  <link href="{{url('/')}}/css/loginQuery.css" rel="stylesheet" />
</head>

<body>
  <div class="page">
    <header>
      <div class="logo-container">
        <picture>
          <source srcset="{{url('/')}}/imgs/logo.webp" type="image/webp" />
          <source srcset="{{url('/')}}/imgs/logo.png" type="image/png" />
          <img class="logo-img" src="{{url('/')}}/img/logo.png" alt="Tabali logo" />
        </picture>
      </div>
    </header>
    <main>
      @yield('main')
    </main>
    <footer>
      <p class="copyright">
        Copyright &copy; <span class="year">2023</span> &ThinSpace; by Hussein
        Medhat, Inc. All rights reserved.
      </p>
    </footer>
  </div>
</body>

</html>