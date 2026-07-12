<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Brand -->
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('images/Official_Seal_of_Southern_Leyte.svg.webp') }}"
             alt="CRSL Seal"
             style="width:36px;height:36px;object-fit:contain;">
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">CRSL</span>
    </a>
    <a href="javascript:void(0);"
       class="crsl-toggle-btn ms-auto"
       data-sidebar-toggle
       title="Toggle sidebar">
      <i class="bx bx-chevron-left"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link"
         data-menu-tooltip="Dashboard" title="Dashboard">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <!-- Handwriting OCR -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Handwriting OCR</span>
    </li>

    <li class="menu-item {{ request()->routeIs('recognitions.create') ? 'active' : '' }}">
      <a href="{{ route('recognitions.create') }}" class="menu-link"
         data-menu-tooltip="New Recognition" title="New Recognition">
        <i class="menu-icon tf-icons bx bx-upload"></i>
        <div>New Recognition</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('recognitions.index', 'recognitions.show', 'recognitions.verify') ? 'active' : '' }}">
      <a href="{{ route('recognitions.index') }}" class="menu-link"
         data-menu-tooltip="Recognition History" title="Recognition History">
        <i class="menu-icon tf-icons bx bx-images"></i>
        <div>Recognition History</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('inference') ? 'active' : '' }}">
      <a href="{{ route('inference') }}" class="menu-link"
         data-menu-tooltip="Inference" title="Inference">
        <i class="menu-icon tf-icons bx bx-brain"></i>
        <div>Inference</div>
      </a>
    </li>

    <!-- Account -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Account</span>
    </li>

    <li class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
      <a href="{{ route('profile.edit') }}" class="menu-link"
         data-menu-tooltip="Profile Settings" title="Profile Settings">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Profile Settings</div>
      </a>
    </li>

  </ul>
</aside>
