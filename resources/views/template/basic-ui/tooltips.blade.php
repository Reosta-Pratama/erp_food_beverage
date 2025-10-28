@extends('layouts.app', [
    'title' => 'Tooltips'
])

@section('styles')
@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">UI Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tooltips</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Tooltip Directions -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tooltip Directions</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tooltip on top">
                            Tooltip on top
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-placement="right"
                            title="Tooltip on right">
                            Tooltip on right
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="Tooltip on bottom">
                            Tooltip on bottom
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-placement="left"
                            title="Tooltip on left">
                            Tooltip on left
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tooltip Directions -->

        <!-- Colored Tooltip -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Colored Tooltip</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-primary"
                            data-bs-placement="top"
                            title="Primary Tooltip">
                            Primary Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-secondary"
                            data-bs-placement="right"
                            title="Secondary Tooltip">
                            Secondary Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-warning"
                            data-bs-placement="bottom"
                            title="Warning Tooltip">
                            Warning Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-info btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-info"
                            data-bs-placement="left"
                            title="Info Tooltip">
                            Info Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-success btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-success"
                            data-bs-placement="top"
                            title="Success Tooltip">
                            Success Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-danger"
                            data-bs-placement="bottom"
                            title="Danger Tooltip">
                            Danger Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-light btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-light"
                            data-bs-placement="bottom"
                            title="Light Tooltip">
                            Light Tooltip
                        </button>
                        <button
                            type="button"
                            class="btn btn-dark text-white btn-wave"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-dark"
                            data-bs-placement="bottom"
                            title="Dark Tooltip">
                            Dark Tooltip
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colored Tooltip -->

        <!-- Tooltip Link -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tooltip Link</div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Hover on the link to view the
                        <a
                            href="javascript:void(0);"
                            data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-primary"
                            title="Link Tooltip"
                            class="text-primary">Tooltip</a>
                    </p>
                </div>
            </div>
        </div>
        <!-- Tooltip Link -->

        <!-- SVG's Tooltip -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">SVG's Tooltip</div>
                </div>
                <div class="card-body">
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Home"
                        data-bs-custom-class="tooltip-primary"
                        class="me-3 svg-primary">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-smart-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M19 8.71l-5.333 -4.148a2.666 2.666 0 0 0 -3.274 0l-5.334 4.148a2.665 2.665 0 0 0 -1.029 2.105v7.2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-7.2c0 -.823 -.38 -1.6 -1.03 -2.105"/><path d="M16 15c-2.21 1.333 -5.792 1.333 -8 0"/></svg>
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Message"
                        data-bs-custom-class="tooltip-secondary"
                        class="me-3 svg-secondary">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-message"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8"/><path d="M8 13h6"/><path
                            d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z"/></svg>
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Add User"
                        data-bs-custom-class="tooltip-warning"
                        class="me-3 svg-warning">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M16 19h6"/><path d="M19 16v6"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4"/></svg>
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Send File"
                        data-bs-custom-class="tooltip-info"
                        class="me-3 svg-info">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11"/><path
                            d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"/></svg>
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Action"
                        data-bs-custom-class="tooltip-success"
                        class="me-3 svg-success">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-dots"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- SVG's Tooltip -->

        <!-- Disabled Tooltip -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled Tooltip</div>
                </div>
                <div class="card-body">
                    <span
                        class="d-inline-block"
                        tabindex="0"
                        data-bs-toggle="tooltip"
                        title="Disabled tooltip">
                        <button class="btn btn-primary" type="button" disabled="">Disabled button
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <!-- Disabled Tooltip -->

        <!-- Images Tooltip -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Images Tooltip</div>
                </div>
                <div class="card-body">
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Rama Aditya Putra"
                        data-bs-custom-class="tooltip-primary"
                        class="avatar avatar-md me-2 online avatar-rounded">
                        <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Sarah Maulida"
                        data-bs-custom-class="tooltip-primary"
                        class="avatar avatar-lg me-2 online avatar-rounded">
                        <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                    </a>
                    <a
                        href="javascript:void(0);"
                        data-bs-toggle="tooltip"
                        title="Dimas Wahyu Pratama"
                        data-bs-custom-class="tooltip-primary"
                        class="avatar avatar-xl me-2 offline avatar-rounded">
                        <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                    </a>
                </div>
            </div>
        </div>
        <!-- Images Tooltip -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection