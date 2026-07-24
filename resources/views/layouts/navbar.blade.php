<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        <span class="fw-semibold text-uppercase" style="letter-spacing:.05em; color:#697a8d; font-size:.85rem;">
          CIVIL REGISTRY SYSTEM OF MASSIN CITY
        </span>
      </div>
    </div>

    <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

      <li class="nav-item">
        <a href="javascript:void(0)" class="nav-link" title="Help">
          <i class="bx bx-help-circle" style="font-size: 1.25rem; color: #8897a5;"></i>
        </a>
      </li>

      <li class="nav-item">
        <a href="javascript:void(0)" class="nav-link" id="btn-fullscreen" title="Fullscreen">
          <i class="bx bx-fullscreen" style="font-size: 1.25rem; color: #8897a5;"></i>
        </a>
      </li>

      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                 style="width:38px;height:38px;"> 
              <span class="text-white fw-bold" style="font-size:15px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </span>
            </div>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <div class="dropdown-header text-dark text-transform-none py-2 px-3" onclick="event.stopPropagation();">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                  <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                      style="width:38px;height:38px;">
                    <span class="text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block text-dark">{{ Auth::user()->name }}</span>
                  <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
              </div>
            </div>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">My Profile</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('recognitions.index') }}">
              <i class="bx bx-images me-2"></i>
              <span class="align-middle">My Recognitions</span>
            </a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Log Out</span>
              </button>
            </form>
          </li>
        </ul>
      </li>

    </ul>
  </div>

  <script>
    document.getElementById('btn-fullscreen').addEventListener('click', function () {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
      } else {
        document.exitFullscreen();
      }
    });
  </script>
</nav>
