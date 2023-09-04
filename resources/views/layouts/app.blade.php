<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tabali CRM - @yield('title')</title>
  <link href="{{url('/')}}/fonts/stylesheet.css" rel="stylesheet" />
  <link href="{{url('/')}}/css/style.css" rel="stylesheet" />
  <link href="{{url('/')}}/css/queries.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Rubik:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <script defer src="{{url('/')}}/js/script.js"></script>
</head>

<body>
  <div class="page">
    <header class="page-header">
      <div class="heading-nav">
        <div class="left-heading">
          <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
          </button>
          <picture class="logo-left">
            <source srcset="{{url('/')}}/imgs/logo-white.webp" type="image/webp" />
            <source srcset="{{url('/')}}/imgs/logo-white.png" type="image/png" />
            <img class="logo-dashboard" src="{{url('/')}}/img/logo-white.png" alt="Tabali logo" />
          </picture>
          <ul class="nav-items">
            <li class="nav-item @if(Route::is('dashboard')) nav-item-cta @endif">
              <a href="/dashboard">Dashboard</a>
            </li>
            <li
              class="nav-item @if(Route::is('teams') || Route::is('team') || Route::is('team-search'))  nav-item-cta @endif">
              <a href="/teams">Teams</a>
            </li>
            <li class="nav-item @if(Route::is('tasks') || Route::is('task')) nav-item-cta @endif"><a
                href="/tasks">Tasks</a></li>
            <li class="nav-item @if(!Auth::user()->role == 'Admin') hidden @endif ">
              <a href="/users">Users</a>
            </li>
          </ul>
        </div>
        <picture class="logo-center">
          <source srcset="{{url('/')}}/imgs/logo-white.webp" type="image/webp" />
          <source srcset="{{url('/')}}/imgs/logo-white.png" type="image/png" />
          <img class="logo-dashboard" src="{{url('/')}}/img/logo-white.png" alt="Tabali logo" />
        </picture>
        <div class="right-heading">
          <p class="welcome-title white-font hide"><span class='smaller-font'>Welcome</span>
            <span>@yield('username')</span>
          </p>
          <form method="POST" action="/logout">
            @csrf
            @method('POST')
            <button title="Logout" class="profile">
              <ion-icon class="logout-icon" name="log-out-outline"></ion-icon>
            </button>
          </form>
        </div>
      </div>

      <div class="heading-bar">
        @yield('heading-bar')
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
    @yield('modals')
  </div>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>