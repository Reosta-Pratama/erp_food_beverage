@extends('layouts.app', [
    'title' => 'List Group'
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
                    <li class="breadcrumb-item active" aria-current="page">List Group</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Basic List -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic List</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <div class="ms-2">
                                    Rama Aditya Putra
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <div class="ms-2">
                                    Sarah Maulida
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                                <div class="ms-2">
                                    Dimas Wahyu Pratama
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm">
                                    <img src="{{ asset('assets/images/avatar/4.jpg') }}" alt="img">
                                </span>
                                <div class="ms-2">
                                    Livia Nurani
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm">
                                    <img src="{{ asset('assets/images/avatar/5.jpg') }}" alt="img">
                                </span>
                                <div class="ms-2">
                                    Fajar Rizky Ramadhan
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Basic List -->

        <!-- Active Items -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Active Items</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item active" aria-current="true">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15">
                                        <i class="ti ti-smart-home"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Home
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15">
                                        <i class="ti ti-bell"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Notifications
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15">
                                        <i class="ti ti-brand-hipchat"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Sent Messages
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15">
                                        <i class="ti ti-user-plus"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    New Requests
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15">
                                        <i class="ti ti-trash"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Deleted Messages
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Active Items -->

        <!-- Disabled Items -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled Items</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item disabled" aria-disabled="true">A disabled item meant to be disabled</li>
                        <li class="list-group-item">Simply dummy text of the printing</li>
                        <li class="list-group-item">There are many variations of passages</li>
                        <li class="list-group-item">All the Lorem Ipsum generators</li>
                        <li class="list-group-item">Written in 45 BC. This book is a treatise on the theory</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Disabled Items -->

        <!-- Flush -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Flush</div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="ri-home-2-line fs-15 lh-1 align-center me-2 text-muted"></i>Asish Trivedhi<span class="ms-1 text-muted d-inline-block">(+1023-84534)</span></li>
                        <li class="list-group-item"><i class="ri-cloud-line fs-15 lh-1 align-center me-2 text-muted"></i>Alezander Russo<span class="ms-1 text-muted d-inline-block">(+7546-12342)</span></li>
                        <li class="list-group-item"><i class="ri-global-line fs-15 lh-1 align-center me-2 text-muted"></i>Karem Smith<span class="ms-1 text-muted d-inline-block">(+9944-56632)</span></li>
                        <li class="list-group-item"><i class="ri-stack-line fs-15 lh-1 align-center me-2 text-muted"></i>Melissa Brien<span class="ms-1 text-muted d-inline-block">(+1023-34323)</span></li>
                        <li class="list-group-item"><i class="ri-gift-2-line fs-15 lh-1 align-center me-2 text-muted"></i>Kamala Harris<span class="ms-1 text-muted d-inline-block">(+91-63421)</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Flush -->

        <!-- Links -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Links</div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action active"
                            aria-current="true">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="avatar avatar-xs bg-white text-default avatar-rounded">
                                        C
                                    </span>
                                </div>
                                <div class="ms-2">California</div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="avatar avatar-xs bg-secondary avatar-rounded">
                                        N
                                    </span>
                                </div>
                                <div class="ms-2">New Jersey</div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="avatar avatar-xs bg-info avatar-rounded">
                                        L
                                    </span>
                                </div>
                                <div class="ms-2">Los Angeles</div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="avatar avatar-xs bg-warning avatar-rounded">
                                        M
                                    </span>
                                </div>
                                <div class="ms-2">Miami Florida</div>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action disabled">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="avatar avatar-xs bg-success avatar-rounded">
                                        W
                                    </span>
                                </div>
                                <div class="ms-2">Washington D.C</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Links -->

        <!-- Buttons -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Buttons</div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action active" aria-current="true">Simply dummy text of the printing<span class="badge float-end bg-primary">243</span></button>
                        <button type="button" class="list-group-item list-group-item-action">There are many variations of passages<span class="badge float-end bg-secondary-transparent">35</span></button>
                        <button type="button" class="list-group-item list-group-item-action">All the Lorem Ipsum generators<span class="badge float-end bg-info-transparent">132</span></button>
                        <button type="button" class="list-group-item list-group-item-action">All the Lorem Ipsum generators<span class="badge float-end bg-success-transparent">2525</span></button>
                        <button type="button" class="list-group-item list-group-item-action" disabled>A disabled item meant to be disabled<span class="badge float-end bg-danger-transparent">21</span></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Buttons -->

        <!-- Contextual Classes -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Contextual Classes</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">A simple default list group item</li>
                        <li class="list-group-item list-group-item-primary">A simple primary list group item</li>
                        <li class="list-group-item list-group-item-secondary">A simple secondary list group item</li>
                        <li class="list-group-item list-group-item-success">A simple success list group item</li>
                        <li class="list-group-item list-group-item-danger">A simple danger list group item</li>
                        <li class="list-group-item list-group-item-warning">A simple warning list group item</li>
                        <li class="list-group-item list-group-item-info">A simple info list group item</li>
                        <li class="list-group-item list-group-item-light">A simple light list group item</li>
                        <li class="list-group-item list-group-item-dark">A simple dark list group item</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Contextual Classes -->

        <!-- Contextual Classes With Hover -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Contextual Classes With Hover</div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">A simple default list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-primary">A simple primary list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-secondary">A simple secondary list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-success">A simple success list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-danger">A simple danger list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-warning">A simple warning list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-info">A simple info list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-light">A simple light list group item</a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action list-group-item-dark">A simple dark list group item</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contextual Classes With Hover -->

        <!-- Solid Color List -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Solid Color List</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">A simple default list group item</li>
                        <li class="list-group-item list-item-solid-primary">A simple primary list group group item</li>
                        <li class="list-group-item list-item-solid-secondary">A simple secondary list group group item</li>
                        <li class="list-group-item list-item-solid-info">A simple info list group group item</li>
                        <li class="list-group-item list-item-solid-success">A simple success list group group item</li>
                        <li class="list-group-item list-item-solid-danger">A simple danger list group group item</li>
                        <li class="list-group-item list-item-solid-warning">A simple warning list group item</li>
                        <li class="list-group-item list-item-solid-light">A simple light list group item</li>
                        <li class="list-group-item list-item-solid-dark text-white">A simple dark list group item</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Solid Color List -->

        <!-- Custom Contents -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Custom Contents</div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a
                            href="javascript:void(0);"
                            class="list-group-item list-group-item-action active"
                            aria-current="true">
                            <div class="d-sm-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-fixed-white">Web page editors now use Lorem Ipsum?</h6>
                                <small>3 days ago</small>
                            </div>
                            <p class="mb-1">There are many variations of passages of Lorem Ipsum available,
                                but the majority have suffered alteration in some form, by injected humour.</p>
                            <small>24,Mar 2024.</small>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-sm-flex w-100 justify-content-between">
                                <h6 class="mb-1">Richard McClintock, a Latin professor?</h6>
                                <small class="text-muted">4 hrs ago</small>
                            </div>
                            <p class="mb-1">Contrary to popular belief, Lorem Ipsum is not simply random
                                text. It has roots in a piece of classical Latin literature.</p>
                            <small class="text-muted">30,Mar 2024.</small>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-sm-flex w-100 justify-content-between">
                                <h6 class="mb-1">It uses a dictionary of over 200 Latin words?</h6>
                                <small class="text-muted">15 hrs ago</small>
                            </div>
                            <p class="mb-1">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            <small class="text-muted">4,Mar 2024.</small>
                        </a>
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                            <div class="d-sm-flex w-100 justify-content-between">
                                <h6 class="mb-1">The standard Lorem Ipsum used since the 1500s?</h6>
                                <small class="text-muted">45 mins ago</small>
                            </div>
                            <p class="mb-1">All the Lorem Ipsum generators on the Internet tend to repeat
                                predefined chunks as necessary, making this the first true generator on the
                                Internet.</p>
                            <small class="text-muted">28,Jul 2024.</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom Contents -->

        <!-- Sub Headings -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Sub Headings</div>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item d-sm-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto text-muted">
                                <div class="fw-medium fs-14 text-default">What Happened?</div>
                                Many experts have recently suggested may exist.
                            </div>
                            <span class="badge bg-primary-transparent">32 Views</span>
                        </li>
                        <li class="list-group-item d-sm-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto text-muted">
                                <div class="fw-medium fs-14 text-default">It Was Amazing!</div>
                                His idea involved taking red.
                            </div>
                            <span class="badge bg-secondary-transparent">52 Views</span>
                        </li>
                        <li class="list-group-item d-sm-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto text-muted">
                                <div class="fw-medium fs-14 text-default">News Is A Great Weapon.</div>
                                News can influence in many ways.
                            </div>
                            <span class="badge bg-success-transparent">1,204 Views</span>
                        </li>
                        <li class="list-group-item d-sm-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto text-muted">
                                <div class="fw-medium fs-14 text-default">majority have suffered.</div>
                                If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything.
                            </div>
                            <span class="badge bg-danger-transparent">14 Views</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- Sub Headings -->

        <!-- Number List -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Number List</div>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">Simply dummy text of the printing.</li>
                        <li class="list-group-item">There are many variations of passages.</li>
                        <li class="list-group-item">All the Lorem Ipsum generators.</li>
                        <li class="list-group-item">Written in 45 BC. This book is a treatise on the theory.</li>
                        <li class="list-group-item">Randomised words which don't look.</li>
                        <li class="list-group-item">Always free from repetition, injected humour.</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- Number List -->

        <!-- Checkboxes -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Checkboxes</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="..." checked>
                                Accurate information at any given point.
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="...">
                                Hearing the information and responding.
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="..." checked>
                                Setting up and customizing your own sales.
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="..." checked>
                                New Admin Launched.
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="...">
                                To maximize profits and improve productivity.
                        </li>
                        <li class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" value=""
                                aria-label="...">
                                To have a complete 360° overview of sales information, having.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Checkboxes -->

        <!-- Radios -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Radios</div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio" checked>
                                Accurate information at any given point.
                        </label>
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio" checked>
                                Hearing the information and responding.
                        </label>
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio" checked>
                                Setting up and customizing your own sales.
                        </label>
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio">
                                New Admin Launched.
                        </label>
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio">
                                To maximize profits and improve productivity.
                        </label>
                        <label class="list-group-item">
                            <input class="form-check-input me-1" type="radio" value=""
                                name="list-radio">
                                To have a complete 360° overview of sales information, having.
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Radios -->

        <!-- Badges -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Badges</div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Groceries
                            <span class="badge bg-primary">Available</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Furniture
                            <span class="badge bg-secondary">Buy</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Beauty
                            <span class="badge bg-success rounded-pill">32</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Books
                            <span class="badge bg-light text-default">New</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Toys
                            <span class="badge bg-info-gradient">Out of Stock</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Electronic Gadgets
                            <span class="badge bg-danger-transparent">&#128293; Hot</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center">
                            Mobiles
                            <span class="badge bg-info">Sold Out</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Badges -->

        <!-- Horizontal -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Horizontal</div>
                </div>
                <div class="card-body">
                    <ul class="mb-2 list-group list-group-horizontal">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="mb-2 list-group list-group-horizontal-sm">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="mb-2 list-group list-group-horizontal-md">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="mb-2 list-group list-group-horizontal-lg">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="mb-2 list-group list-group-horizontal-xl">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <ul class="mb-0 list-group list-group-horizontal-xxl">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Horizontal -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection