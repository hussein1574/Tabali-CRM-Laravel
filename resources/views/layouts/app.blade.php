<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="Tabali CRM is an web app that helps to manage the tasks between mangers and employees" />
  <link rel="icon" href="{{url('/')}}/imgs/logo.png" />
  <link rel="apple-touch-icon" href="{{url('/')}}/imgs/apple-touch-icon-iphone-60x60.png">
  <link rel="apple-touch-icon" sizes="60x60" href="{{url('/')}}/imgs/apple-touch-icon-ipad-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="{{url('/')}}/imgs/apple-touch-icon-iphone-retina-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="{{url('/')}}/imgs/apple-touch-icon-ipad-retina-152x152.png">
  <link rel="manifest" href="{{url('/')}}/mainfest.webmanifest" />
  <title>Tabali CRM - @yield('title')</title>
  <link href="{{url('/')}}/fonts/stylesheet.css" rel="stylesheet" />
  @if(session('locale')=='ar' )
  <link href="{{url('/')}}/css/style-ar.css" rel="stylesheet" />
  @else
  <link href="{{url('/')}}/css/style.css" rel="stylesheet" />
  @endif
  <link href="{{url('/')}}/css/queries.css" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Rubik:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Libre+Baskerville:wght@400;700&family=Rubik:wght@400;500;600;700;800&display=swap"
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
          <a class="logo-left" href='{{url('/')}}'>
            <picture>
              <source srcset="{{url('/')}}/imgs/logo-white.webp" type="image/webp" />
              <source srcset="{{url('/')}}/imgs/logo-white.png" type="image/png" />
              <img class="logo-dashboard" src="{{url('/')}}/img/logo-white.png" alt="Tabali logo" />
            </picture>
          </a>
          <ul class="nav-items">
            <li class='nav-item title appear-mobile'>
              <p class="welcome-title-mobile"><span class='smaller-font-mobile'>{{__('messages.welcome')}}</span>
                <span>@yield('username')</span>
              </p>
            </li>
            <li class="nav-item @if(Route::is('dashboard')) nav-item-cta @endif">
              <a href="/dashboard">{{__('messages.dashboard')}}</a>
            </li>
            <li
              class="nav-item @if(Route::is('teams') || Route::is('team') || Route::is('team-search'))  nav-item-cta @endif">
              <a href="/teams">{{__('messages.teams')}}</a>
            </li>
            <li
              class="nav-item @if(Route::is('projects')) nav-item-cta @endif @if(Auth::user()->role == 'User')  hidden @endif">
              <a href="/projects">{{__('messages.projects')}}</a>
            </li>
            <li class="nav-item @if(Route::is('tasks') || Route::is('task')) nav-item-cta @endif"><a
                href="/tasks">{{__('messages.tasks')}}</a></li>

            <li
              class="nav-item @if(Route::is('users')) nav-item-cta @endif @if(Auth::user()->role == 'User')  hidden @endif">
              <a href="/users">{{__('messages.usersTitle')}}</a>
            </li>
            <li class="nav-item logout-appear-mobile">
              <form method="POST" action="/logout">
                @csrf
                @method('POST')
                <button title="Logout" class='logout-nav'>
                  {{__('messages.logout')}}
                </button>
              </form>
            </li>
            <li class='nav-item empty-li'>
            </li>
          </ul>
        </div>
        <a class="logo-center" href='{{url('/')}}'>
          <picture>
            <source srcset="{{url('/')}}/imgs/logo-white.webp" type="image/webp" />
            <source srcset="{{url('/')}}/imgs/logo-white.png" type="image/png" />
            <img class="logo-dashboard" src="{{url('/')}}/img/logo-white.png" alt="Tabali logo" />
          </picture>
        </a>
        <div class="right-heading">
          <form class="language-form" method='POST' action="{{route('change-locale')}}">
            @csrf
            @method('POST')
            <select class="language-box" name="locale" id="locale">
              <option @if(session('locale')=='en' ) selected @endif value="en">
                <span class='language-name'>{{__('messages.english')}}</span>
              </option>
              <option @if(session('locale')=='ar' ) selected @endif value="ar">
                <span class='language-name'>{{__('messages.arabic')}}</span>
              </option>
            </select>
          </form>
          <p class="welcome-title white-font hide"><span class='smaller-font'>{{__('messages.welcome')}}</span>
            <span>@yield('username')</span>
          </p>

          <form method="POST" class='logout-form' action="/logout">
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
        {{__('messages.copyrights')}}
      </p>
    </footer>
    @yield('modals')
  </div>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>