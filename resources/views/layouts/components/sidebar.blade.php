<aside id="sidebar" class="app-sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="javascript:void(0)">
            <div class="img-sidebar-open">
                <img src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo {{ config('app.name') }}">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <img class="img-sidebar-close" src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo {{ config('app.name') }}">
        </a>

        <div class="sidebar-toggle">
            <button data-bs-toggle="sidebar" class="btn btn-icon btn-wave">
                <i class="ti ti-chevrons-left"></i>
            </button>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div id="sidebar-content" class="sidebar-content">
        <nav class="menu">
            <ul class="menu-list">

                <!-- Dashboard -->
                @include('layouts.components.partial-sidebar.dashboard')

                <!-- Employee Self Service (All Users) -->
                @include('layouts.components.partial-sidebar.employee-self-service')

                <!-- Human Resources (Admin + Finance_HR) -->
                @include('layouts.components.partial-sidebar.human-resources')

                <!-- Operations (Admin + Operator) -->
                @include('layouts.components.partial-sidebar.operations')

                <!-- Business (Split by Role) -->
                @include('layouts.components.partial-sidebar.business-operations')

                <!-- Finance (Admin + Finance_HR) -->
                @include('layouts.components.partial-sidebar.finance')

                <!-- Reports (All Roles) -->
                @include('layouts.components.partial-sidebar.reports')

                <!-- Communication (All Users) -->
                @include('layouts.components.partial-sidebar.communication')

                <!-- System Administration (Admin Only) -->
                @include('layouts.components.partial-sidebar.system-administration')

            </ul>
        </nav>
    </div>
</aside>