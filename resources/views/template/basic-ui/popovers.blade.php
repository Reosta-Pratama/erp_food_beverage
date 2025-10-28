@extends('layouts.app', [
    'title' => 'Popovers'
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
                    <li class="breadcrumb-item active" aria-current="page">Popovers</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Default -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <a
                            tabindex="0"
                            class="btn btn-outline-primary btn-wave"
                            role="button"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            title="Popover Top"
                            data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Top
                        </a>
                        <a
                            tabindex="0"
                            class="btn btn-outline-primary btn-wave"
                            role="button"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            title="Popover Right"
                            data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Right</a>
                        <a
                            tabindex="0"
                            class="btn btn-outline-primary btn-wave"
                            role="button"
                            data-bs-toggle="popover"
                            data-bs-placement="bottom"
                            title="Popover Bottom"
                            data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Bottom</a>
                        <a
                            tabindex="0"
                            class="btn btn-outline-primary btn-wave"
                            role="button"
                            data-bs-toggle="popover"
                            data-bs-placement="left"
                            title="Popover Left"
                            data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Left</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Colored Header -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Colored Header</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="header-primary"
                            title="Color Header"
                            data-bs-content="Popover with primary header.">
                            Header Primary
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-secondary btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-custom-class="header-secondary"
                            title="Color Header"
                            data-bs-content="Popover with secondary header.">
                            Header Secondary
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-info btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="bottom"
                            data-bs-custom-class="header-info"
                            title="Color Header"
                            data-bs-content="Popover with info header.">
                            Header Info
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-warning btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="left"
                            data-bs-custom-class="header-warning"
                            title="Color Header"
                            data-bs-content="Popover with warning header.">
                            Header Warning
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-success btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="header-success"
                            title="Color Header"
                            data-bs-content="Popover with success header.">
                            Header Success
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="header-danger"
                            title="Color Header"
                            data-bs-content="Popover with danger header.">
                            Header Danger
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colored Header -->

        <!-- Colored Popovers -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Colored Popovers</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button
                            type="button"
                            class="btn btn-primary btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="popover-primary"
                            title="Color Background"
                            data-bs-content="Popover with primary background.">
                            Primary
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-custom-class="popover-secondary"
                            title="Color Background"
                            data-bs-content="Popover with secondary background.">
                            Secondary
                        </button>
                        <button
                            type="button"
                            class="btn btn-info btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="bottom"
                            data-bs-custom-class="popover-info"
                            title="Color Background"
                            data-bs-content="Popover with info background.">
                            Info
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="left"
                            data-bs-custom-class="popover-warning"
                            title="Color Background"
                            data-bs-content="Popover with warning background.">
                            Warning
                        </button>
                        <button
                            type="button"
                            class="btn btn-success btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="popover-success"
                            title="Color Background"
                            data-bs-content="Popover with success background.">
                            Success
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-custom-class="popover-danger"
                            title="Color Background"
                            data-bs-content="Popover with danger background.">
                            Danger
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colored Popovers -->

        <!-- Light Colored Popovers -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Light Colored Popovers</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button
                            type="button"
                            class="btn btn-primary-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="popover-primary-light"
                            title="Light Background"
                            data-bs-content="Popover with light primary background.">
                            Primary
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-custom-class="popover-secondary-light"
                            title="Light Background"
                            data-bs-content="Popover with light secondary background.">
                            Secondary
                        </button>
                        <button
                            type="button"
                            class="btn btn-info-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="bottom"
                            data-bs-custom-class="popover-info-light"
                            title="Light Background"
                            data-bs-content="Popover with light info background.">
                            Info
                        </button>
                        <button
                            type="button"
                            class="btn btn-warning-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="left"
                            data-bs-custom-class="popover-warning-light"
                            title="Light Background"
                            data-bs-content="Popover with light warning background.">
                            Warning
                        </button>
                        <button
                            type="button"
                            class="btn btn-success-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-custom-class="popover-success-light"
                            title="Light Background"
                            data-bs-content="Popover with light success background.">
                            Success
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger-light btn-wave"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-custom-class="popover-danger-light"
                            title="Light Background"
                            data-bs-content="Popover with light danger background.">
                            Danger
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Light Colored Popovers -->

        <!-- Dismissible Popovers -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dismissible Popovers</div>
                </div>
                <div
                    class="card-body d-flex flex-wrap justify-content-between">
                    <a
                        tabindex="0"
                        class="btn btn-primary m-1"
                        role="button"
                        data-bs-toggle="popover"
                        data-bs-trigger="focus"
                        data-bs-placement="top"
                        title="Dismissible popover"
                        data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Dismiss
                    </a>
                    <a
                        tabindex="0"
                        class="btn btn-secondary m-1"
                        role="button"
                        data-bs-toggle="popover"
                        data-bs-trigger="focus"
                        data-bs-placement="right"
                        title="Dismissible popover"
                        data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Dismiss
                    </a>
                    <a
                        tabindex="0"
                        class="btn btn-info m-1"
                        role="button"
                        data-bs-toggle="popover"
                        data-bs-trigger="focus"
                        data-bs-placement="bottom"
                        title="Dismissible popover"
                        data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Dismiss
                    </a>
                    <a
                        tabindex="0"
                        class="btn btn-warning m-1"
                        role="button"
                        data-bs-toggle="popover"
                        data-bs-trigger="focus"
                        data-bs-placement="left"
                        title="Dismissible popover"
                        data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Dismiss
                    </a>
                </div>
            </div>
        </div>
        <!-- Dismissible Popovers -->

        <!-- Disabled Popovers -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled Popovers</div>
                </div>
                <div class="card-body">
                    <span
                        class="d-inline-block"
                        tabindex="0"
                        data-bs-toggle="popover"
                        data-bs-trigger="hover focus"
                        data-bs-content="Disabled popover">
                        <button class="btn btn-primary" type="button" disabled="disabled">Disabled button</button>
                    </span>
                </div>
            </div>
        </div>
        <!-- Disabled Popovers -->

        <!-- Icon Popovers -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Icon Popovers</div>
                </div>
                <div class="card-body">
                    <a
                        class="me-4 svg-primary"
                        href="javascript:void(0)"
                        data-bs-toggle="popover"
                        data-bs-placement="top"
                        data-bs-custom-class="popover-primary only-body"
                        data-bs-content="This popover is used to provide details about this icon.">
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
                            class="icon icon-tabler icons-tabler-outline icon-tabler-help-square-rounded"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/><path d="M12 16v.01"/><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483"/></svg>
                    </a>
                    <a
                        class="me-4 svg-secondary"
                        href="javascript:void(0)"
                        data-bs-toggle="popover"
                        data-bs-placement="left"
                        data-bs-custom-class="popover-secondary only-body"
                        data-bs-content="This popover is used to provide information about this icon.">
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
                            class="icon icon-tabler icons-tabler-outline icon-tabler-info-square-rounded"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- Icon Popovers -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection