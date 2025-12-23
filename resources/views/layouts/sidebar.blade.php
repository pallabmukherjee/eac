<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header mb-5">
            <a href="{{ url('dashboard') }}" class="b-brand">
                <div class="text-center p-2">
                    @if (Str::is('superadmin.gratuity.*', Route::currentRouteName()))
                        <h1 class="fw-bold mt-2" style="
                            font-size: 2.2rem;
                            background: linear-gradient(to right, #ff0000, #a020f0, #0000ff);
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                            display: inline-block;
                            position: relative;
                            z-index: 1;
                            letter-spacing: 1px;
                            white-space: nowrap;
                            filter: drop-shadow(0 0 5px rgba(0, 191, 255, 0.6));
                        ">
                            Gratuity
                        </h1>
                    @else
                        <div class="rounded p-3" style="background-color: #f0f8ff;">
                            <!-- ========   Change your logo from here   ============ -->
                            @php
                            $websiteSetting = \App\Models\WebsiteSetting::first();
                            @endphp
                            @if ($websiteSetting && $websiteSetting->logo)
                                <img src="{{ asset('storage/' . $websiteSetting->logo) }}" height="180" width="220" class="img-fluid">
                            @elseif (!$websiteSetting)
                                <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg">
                            @endif
                            <h3 class="fw-bold mt-2" style="color: #003366;">
                                {{ $websiteSetting ? ($websiteSetting->name ?? '') : 'E-Accounting' }}
                            </h3>
                        </div>
                    @endif
                </div>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @if (Auth::check())
                    @switch(Auth::user()->role)
                        @case('SuperAdmin')
                            @if (Str::is('superadmin.account.*', Route::currentRouteName()))
                                @include('layouts.menu-list.accounts')
                            @elseif (Str::is(['superadmin.leave.*'], Route::currentRouteName()))
                                @include('layouts.menu-list.leave')
                            @elseif (Str::is('superadmin.pension.*', Route::currentRouteName()))
                                @include('layouts.menu-list.pension')
                            @elseif (Str::is('superadmin.gratuity.*', Route::currentRouteName()))
                                @include('layouts.menu-list.gratuity')
                            @else
                                @include('layouts.menu-list.super-admin')
                            @endif
                        @break

                        @case('Admin')
                            @if (Str::is('superadmin.account.*', Route::currentRouteName()))
                                @include('layouts.menu-list.admin-account')
                            @elseif (Str::is(['superadmin.leave.*'], Route::currentRouteName()))
                                @include('layouts.menu-list.leave')
                            @elseif (Str::is('superadmin.pension.*', Route::currentRouteName()))
                                @include('layouts.menu-list.pension')
                            @elseif (Str::is('superadmin.gratuity.*', Route::currentRouteName()))
                                @include('layouts.menu-list.gratuity')
                            @else
                                @include('layouts.menu-list.admin')
                            @endif
                        @break

                        @default
                            @if (Request::is('account/*'))
                                @include('layouts.menu-list.menu-list-account')
                            @else
                                @include('layouts.menu-list.menu-list')
                            @endif
                    @endswitch
                @endif
            </ul>
        </div>
        <div class="card pc-user-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ URL::asset('assets/images/user/avatar-1.jpg') }}" alt="user-image"
                            class="user-avtar wid-45 rounded-circle">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="dropdown">
                            <a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" data-bs-offset="0,20">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 me-2">
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small>{{ Auth::user()->role }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="btn btn-icon btn-link-secondary avtar">
                                            <i class="ph-duotone ph-windows-logo"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li>
                                        <a class="pc-user-links">
                                            <i class="ph-duotone ph-user"></i>
                                            <span>My Account</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="pc-user-links" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                            <i class="ph-duotone ph-power"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
