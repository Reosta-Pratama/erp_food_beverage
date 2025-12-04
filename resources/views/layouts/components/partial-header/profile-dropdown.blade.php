<ul class="profile-dropdown dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuProfile">
    {{-- User Info Section --}}
    <li class="info-profile">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar avatar-md avatar-font">
                <span>{{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}</span>
            </span>
            <div class="d-flex flex-column">
                <h6 class="mb-0 fs-14">{{ Auth::user()->full_name }}</h6>
                <span class="text-muted fs-12">{{ Auth::user()->email }}</span>
                <span class="badge bg-primary-transparent fs-10 mt-1">
                    {{ ucfirst(str_replace('_', ' ', Auth::user()->role->role_name)) }}
                </span>
            </div>
        </div>
    </li>

    {{-- Menu Items --}}
    <li class="menu-profile">
        <ul>
            {{-- My Profile --}}
            <li>
                <a class="{{ request()->routeIs('employee.profile') ? 'active' : '' }}" 
                    href="{{ route('employee.profile') }}">
                    <i class="ti ti-user"></i>
                    <span>My Profile</span>
                </a>
            </li>

            {{-- My Attendance --}}
            <li>
                <a class="{{ request()->routeIs('employee.attendance') ? 'active' : '' }}" 
                    href="{{ route('employee.attendance') }}">
                    <i class="ti ti-calendar-check"></i>
                    <span>My Attendance</span>
                </a>
            </li>

            {{-- My Leave --}}
            <li>
                <a class="{{ request()->routeIs('employee.leaves') ? 'active' : '' }}" 
                    href="{{ route('employee.leaves') }}">
                    <i class="ti ti-plane-departure"></i>
                    <span>My Leave</span>
                </a>
            </li>

            {{-- My Payslips --}}
            <li>
                <a class="{{ request()->routeIs('employee.payslips') ? 'active' : '' }}" 
                    href="{{ route('employee.payslips') }}">
                    <i class="ti ti-receipt-2"></i>
                    <span>My Payslips</span>
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            {{-- Announcements (All Users) --}}
            <li>
                <a class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}" 
                    href="javascript:void(0)">
                    <i class="ti ti-speakerphone"></i>
                    <span>Announcements</span>
                    @php
                        // Example: Get unread announcement count
                        $unreadCount = 3; // Replace with actual count from database
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger-transparent ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            {{-- My Meetings --}}
            <li>
                <a class="{{ request()->routeIs('meetings.*') ? 'active' : '' }}" 
                    href="javascript:void(0)">
                    <i class="ti ti-calendar-event"></i>
                    <span>My Meetings</span>
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            {{-- Change Password --}}
            <li>
                <a class="{{ request()->routeIs('employee.change-password') ? 'active' : '' }}" 
                    href="javascript:void(0)">
                    <i class="ti ti-lock"></i>
                    <span>Change Password</span>
                </a>
            </li>

            {{-- Notification Settings --}}
            <li>
                <a class="{{ request()->routeIs('employee.notification-settings') ? 'active' : '' }}" 
                    href="javascript:void(0)">
                    <i class="ti ti-bell"></i>
                    <span>Notification Settings</span>
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            {{-- Help & Support --}}
            <li>
                <a href="javascript:void(0)">
                    <i class="ti ti-help"></i>
                    <span>Help & Support</span>
                </a>
            </li>

            {{-- Documentation --}}
            <li>
                <a href="javascript:void(0)" target="_blank">
                    <i class="ti ti-book"></i>
                    <span>Documentation</span>
                    <i class="ti ti-external-link ms-auto"></i>
                </a>
            </li>
        </ul>
    </li>

    {{-- Sign Out --}}
    <li class="sign-out-profile">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm btn-wave w-100">
                <i class="ti ti-logout me-2"></i>
                Sign Out
            </button>
        </form>
    </li>
</ul>