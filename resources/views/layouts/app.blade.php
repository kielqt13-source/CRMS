<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('sneat/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRSL') }} | @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/img/favicon/favicon.ico') }}" />

    <!-- Fonts: Public Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    @stack('page-css')

    <style>
      /* ─── Font override ─── */
      html, body, * { font-family: 'Public Sans', sans-serif !important; }

      /* ─── Brand text uppercase ─── */
      .app-brand-text { text-transform: uppercase !important; }

      /* ─── Sidebar background (SIS light grey) ─── */
      .layout-menu.bg-menu-theme {
        background: #f5f7fb !important;
        border-right: 1px solid #e6ebf3;
      }
      .layout-menu.bg-menu-theme .menu-inner > .menu-item > .menu-link,
      .layout-menu.bg-menu-theme .menu-header-text {
        color: #697a8d;
      }

      /* ─── Toggle button: solid blue circle (SIS style) ─── */
      .crsl-toggle-btn {
        width: 28px;
        height: 28px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff !important;
        background: #696cff !important;
        transition: background .2s ease;
        flex-shrink: 0;
        text-decoration: none !important;
        cursor: pointer;
      }
      .crsl-toggle-btn:hover { background: #5f61e6 !important; }
      .crsl-toggle-btn i {
        font-size: .9rem;
        display: block;
        line-height: 1;
        transition: transform .3s ease;
      }

      /* Chevron flips to point right when collapsed → indicates "expand" */
      html.layout-menu-collapsed .layout-menu .crsl-toggle-btn i.bx-chevron-left {
        transform: rotate(180deg);
      }

      /* ═══════════════════════════════════════════════════════════════
         COLLAPSED SIDEBAR  (desktop only, not during hover-preview)
         ═══════════════════════════════════════════════════════════════ */
      @media (min-width: 1200px) {

        /* 1 ─ Section headers: gone */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-header {
          display: none !important;
        }

        /* 2 ─ Label text + sub-arrow: gone */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-link > div,
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-link::after {
          display: none !important;
          width: 0 !important;
          overflow: hidden !important;
        }

        /* 3 ─ Brand "CRSL" text: gone */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .app-brand-text {
          display: none !important;
        }

        /* 4 ─ Toggle chevron button: hidden
               Re-appears automatically when user hovers the sidebar
               (Sneat adds layout-menu-hover → :not() stops matching → button shows) */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .crsl-toggle-btn {
          display: none !important;
        }

        /* 5 ─ Brand area: centre the logo image */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .app-brand {
          justify-content: center !important;
          padding: 0.9rem 0 !important;
          height: auto !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .app-brand-link {
          margin: 0 auto !important;
        }

        /* 6 ─ Menu list: full-width items, icon centred */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-inner {
          padding: 0.2rem 0 !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item {
          margin: 0 !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item > .menu-link {
          width: 100% !important;
          height: 2.85rem !important;
          padding: 0 !important;
          border-radius: 0 !important;
          margin: 0 !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          transition: background .15s ease !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item > .menu-link .menu-icon {
          font-size: 1.3rem !important;
          margin: 0 !important;
          color: #8897a5 !important;
        }

        /* 7 ─ Hover state */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item:hover > .menu-link {
          background: rgba(105,108,255,0.08) !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item:hover > .menu-link .menu-icon {
          color: #696cff !important;
        }

        /* 8 ─ Active state: full-width blue (SIS style) */
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item.active > .menu-link {
          background: rgba(105,108,255,0.14) !important;
        }
        html.layout-menu-collapsed:not(.layout-menu-hover) #layout-menu .menu-item.active > .menu-link .menu-icon {
          color: #696cff !important;
        }
      }
    </style>

    <!-- Helpers -->
    <script src="{{ asset('sneat/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/js/config.js') }}"></script>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        @include('layouts.sidebar')

        <div class="layout-page">
          @include('layouts.navbar')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">

              @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                  {{ session('status') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              {{ $slot }}

            </div>



            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>

      <!-- Mobile overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('sneat/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat/js/main.js') }}"></script>

    <script>
    (function () {
      'use strict';

      var html       = document.documentElement;
      var STORAGE    = 'crsl_sidebar_collapsed';

      function isDesktop()  { return window.innerWidth >= 1200; }
      function isCollapsed(){ return html.classList.contains('layout-menu-collapsed'); }

      /* ── apply collapse state via Sneat Helpers ── */
      function applyCollapse(collapse) {
        if (window.Helpers && typeof window.Helpers.setCollapsed === 'function') {
          window.Helpers.setCollapsed(collapse, false);
        } else {
          html.classList.toggle('layout-menu-collapsed', collapse);
          html.classList.toggle('layout-menu-expanded', !collapse);
        }
      }

      /* ── restore preference (default: EXPANDED) ── */
      if (isDesktop()) {
        var saved = localStorage.getItem(STORAGE);
        applyCollapse(saved === '1');   // only collapse if explicitly saved as '1'
      }

      /* ── toggle on button click ── */
      document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-sidebar-toggle]');
        if (!btn) return;
        e.preventDefault();
        var next = !isCollapsed();
        applyCollapse(next);
        if (isDesktop()) localStorage.setItem(STORAGE, next ? '1' : '0');
        syncTooltips();
      });

      /* ── right-click tooltips in icon-only mode ── */
      function syncTooltips() {
        var on = isDesktop() && isCollapsed();
        document.querySelectorAll('[data-menu-tooltip]').forEach(function (el) {
          var tip = bootstrap.Tooltip.getInstance(el);
          if (on) {
            el.setAttribute('data-bs-toggle', 'tooltip');
            el.setAttribute('data-bs-placement', 'right');
            if (!tip) new bootstrap.Tooltip(el, { trigger: 'hover' });
          } else {
            if (tip) tip.dispose();
            el.removeAttribute('data-bs-toggle');
            el.removeAttribute('data-bs-placement');
          }
        });
      }

      /* re-sync when Sneat hover mode changes class on <html> */
      new MutationObserver(syncTooltips)
        .observe(html, { attributes: true, attributeFilter: ['class'] });

      window.addEventListener('resize', function () {
        if (isDesktop()) applyCollapse(localStorage.getItem(STORAGE) === '1');
        syncTooltips();
      });

      syncTooltips();
    })();
    </script>

    @stack('page-js')
  </body>
</html>
