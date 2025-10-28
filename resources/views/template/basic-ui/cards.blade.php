@extends('layouts.app', [
    'title' => 'Cards'
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
                        <a href="javascript:void(0);">Basic UI</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Cards</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gy-4">
        <!-- Default -->
        <div class="col-12">
            <h6 class="mb-3">Default Card:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Card title</h6>
                            <p class="card-text">Some quick example text to build on the card title and make
                                up the bulk of the card's content.</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                        </ul>
                        <div class="card-body">
                            <a href="javascript:void(0);" class="card-link text-primary">Card link</a>
                            <a href="javascript:void(0);" class="card-link text-secondary d-inline-block">Another link</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Card title</h6>
                            <p class="card-text text-muted">As the wind whistled through the dense foliage,
                                scattering leaves like whispered secrets, a lone sapling stood resilient, its
                                roots anchored deep in the earth.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Read More</a>
                        </div>
                        <div class="card-footer">
                            <span class="card-text">Last updated 3 mins ago</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <p class="card-text">Some quick example text to build on the card title and make
                                up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Featured</div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Special title treatment</h6>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                            </p>
                            <a href="javascript:void(0);" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom">
                        <div class="card-body">
                            <h6 class="card-title fw-medium mb-2">Card title</h6>
                            <p class="card-subtitle mb-3 text-muted">Card subtitle</p>
                            <p class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="javascript:void(0);" class="card-link text-danger m-1">Buy Now</a>
                            <a href="javascript:void(0);" class="card-link text-success m-1">
                                <u>Review</u>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Only Card Body -->
        <div class="col-12">
            <h6 class="mb-3">Only Card Body:</h6>

            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card custom">
                        <div class="card-body">
                            <div class="card-text">
                                <p class="mb-0">It is a long established fact that a reader will be distracted
                                    by the readable content.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Only Card Body -->

        <!-- Using Grid Markups -->
        <div class="col-12">
            <h6 class="mb-3">Using Grid Markups:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium">Product A</h6>
                            <p class="card-text">With supporting text below as a natural
                                lead-in to additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Purchase</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium">Product B</h6>
                            <p class="card-text">With supporting text below as a natural
                                lead-in to additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-secondary">Purchase</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium">Product C</h6>
                            <p class="card-text">With supporting text below as a natural
                                lead-in to additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-danger">Purchase</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Using Grid Markups -->

        <!-- Quote -->
        <div class="col-12">
            <h6 class="mb-3">Quote:</h6>

            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card custom">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0 text-center">
                                <h6>
                                    In the darkest seasons of life, when burdens feel endless and hope seems faint,
                                    remember: every trial you face is shaping strength you have yet to understand.
                                    The mountains may be steep and the nights long, but dawn always comes — and you
                                    will rise, not broken, but beautifully rebuilt.
                                </h6>
                                <footer class="blockquote-footer mt-2 fs-14">From
                                    <cite title="Source Title">ChatGPT</cite>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom bg-secondary">
                        <div class="card-body text-fixed-white">
                            <blockquote class="blockquote mb-0 text-center">
                                <h6>
                                    In the darkest seasons of life, when burdens feel endless and hope seems faint,
                                    remember: every trial you face is shaping strength you have yet to understand.
                                    The mountains may be steep and the nights long, but dawn always comes — and you
                                    will rise, not broken, but beautifully rebuilt.
                                </h6>
                                <footer class="blockquote-footer mt-2 fs-14 text-fixed-white op-7">From
                                    <cite title="Source Title">ChatGPT</cite>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quote -->

        <!-- List Group -->
        <div class="col-12">
            <h6 class="mb-3">List Group:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom overflow-hidden">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card custom overflow-hidden">
                        <div class="card-header">
                            Featured
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card custom overflow-hidden">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <div class="card-footer border-top-0">
                            Card footer
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- List Group -->

        <!-- Card Header & Footer -->
        <div class="col-12">
            <h6 class="mb-3">Card Header & Footer:</h6>

            <div class="row gx-4">
                <div class="col-xl-3">
                    <div class="card custom text-center">
                        <div class="card-header border-bottom-0 pb-0">
                            <span class="ms-auto shadow-lg fs-17">
                                <i class="ti ti-heart-filled text-danger"></i>
                            </span>
                        </div>
                        <div class="card-body pt-1">
                            <span class="avatar avatar-xl avatar-rounded me-2 mb-2">
                                <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                            </span>
                            <div class="fw-medium fs-16">ChatGPT</div>
                            <p class="mb-4 text-muted fs-11">Expert Web Developer</p>
                            <div class="btn-list">
                                <button class="btn btn-icon btn-facebook btn-wave">
                                    <i class="ti ti-brand-facebook"></i>
                                </button>
                                <button class="btn btn-icon btn-twitter btn-wave">
                                    <i class="ri-twitter-x-line"></i>
                                </button>
                                <button class="btn btn-icon btn-instagram btn-wave">
                                    <i class="ti ti-brand-instagram"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card custom border border-primary">
                        <div class="card-body">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="50"
                                height="50"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-brand-vscode text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 3v18l4 -2.5v-13z"/><path
                                d="M9.165 13.903l-4.165 3.597l-2 -1l4.333 -4.5m1.735 -1.802l6.932 -7.198v5l-4.795 4.141"/><path d="M16 16.5l-11 -10l-2 1l13 13.5"/>
                            </svg>
                            <p class="mb-0 mt-3 fs-20 fw-medium lh-1">
                                Calculations
                            </p>
                        </div>
                        <div class="card-footer">
                            Quickly and easily generate Lorem Ipsum placeholder text. Select the number of characters, words, sentences or paragraphs, and hit generate!
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="d-flex align-items-center w-100">
                                <div class="me-2">
                                    <span class="avatar avatar-rounded">
                                        <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="">
                                    <div class="fs-15 fw-medium">ChatGPT</div>
                                    <p class="mb-0 text-muted fs-11">2 Years, Unknown</p>
                                </div>
                                <div class="dropdown ms-auto">
                                    <a
                                        href="javascript:void(0);"
                                        class="btn btn-icon btn-sm btn-light"
                                        data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Week</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Month</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);">Year</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            Quickly and easily generate Lorem Ipsum placeholder text. Select the number of characters, words, sentences or paragraphs, and hit generate!
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div class="fs-semibold fs-14">28,Mar 2024</div>
                                <div class="fw-medium text-success">Assistant Professor</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card custom">
                        <div class="card-header border-bottom-0 pb-0">
                            <div>
                                <span class="text-warning me-1">
                                    <i class="ti ti-star-filled"></i>
                                </span>
                                <span class="text-warning me-1">
                                    <i class="ti ti-star-filled"></i>
                                </span>
                                <span class="text-warning me-1">
                                    <i class="ti ti-star-filled"></i>
                                </span>
                                <span class="text-warning me-1">
                                    <i class="ti ti-star-filled"></i>
                                </span>
                                <span class="text-black op-1">
                                    <i class="ti ti-star-filled"></i>
                                </span>
                                <p class="d-block text-muted mb-0 fs-12 fw-medium">1 year ago</p>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="fw-medium fs-15 mb-2">Very Great!</div>
                            Quickly and easily generate Lorem Ipsum placeholder text. Select the number of characters, words, sentences or paragraphs, and hit generate!
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm avatar-rounded me-2">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                                <div class="fw-medium fs-14">ChatGPT</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom text-center">
                        <div class="card-header">
                            <div class="card-title">Featured</div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-medium mb-2">Breaking News !</h6>
                            <p class="card-text mb-4">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-primary mt-2">Read More</a>
                            <a href="javascript:void(0);" class="btn btn-outline-secondary mt-2">Close</a>
                        </div>
                        <div class="card-footer text-muted">
                            11.32pm
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="d-flex w-100">
                                <div class="me-4">
                                    <span class="avatar avatar-lg avatar-rounded">
                                        <img src="{{ asset('assets/images/avatar/4.jpg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                                    <div class="me-3">
                                        <p class="text-muted mb-0">Posts</p>
                                        <p class="fw-medium fs-16 mb-0">25</p>
                                    </div>
                                    <div class="me-3">
                                        <p class="text-muted mb-0">Followers</p>
                                        <p class="fw-medium fs-16 mb-0">1253</p>
                                    </div>
                                    <div class="me-3">
                                        <p class="text-muted mb-0">Following</p>
                                        <p class="fw-medium fs-16 mb-0">367</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fw-medium fs-16">ChatGPT</div>
                            <div class="text-muted fs-11 mb-4">Expert Web Developer</div>
                            <p class="fs-14 fw-medium mb-1">About:</p>
                            <p class="mb-0 card-text">
                                Quickly and easily generate Lorem Ipsum placeholder text. Select the number of characters, words, sentences or paragraphs, and hit generate!
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium mb-3">
                                Mountains
                                <span class="badge bg-primary float-end fs-10">New</span>
                            </h6>
                            <p class="card-text">With supporting text below as a natural lead-in.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium mb-3">
                                Mountains
                                <span class="badge bg-danger float-end fs-10">Hot</span>
                            </h6>
                            <p class="card-text">With supporting text below as a natural lead-in.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img mb-3" alt="...">
                            <h6 class="card-title fw-medium mb-3">
                                Mountains
                                <span class="badge bg-secondary float-end fs-10">Offer</span>
                            </h6>
                            <p class="card-text">With supporting text below as a natural lead-in.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-header justify-content-between border-bottom-0">
                            <div class="card-title">
                                Card With Collapse Button
                            </div>
                            <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                                <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                            </a>
                        </div>
                        <div class="collapse show border-top" id="collapseExample">
                            <div class="card-body">
                                <h6 class="card-text fw-medium">Collapsible Card</h6>
                                <p class="card-text mb-0">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words</p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Read More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Card With Close Button
                            </div>
                            <a href="javascript:void(0);" data-bs-toggle="card-remove">
                                <i class="ri-close-line fs-18"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="card-text fw-medium">Closed Card</h6>
                            <p class="card-text mb-0">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words</p>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Read More</button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Card With Fullscreen Button
                            </div>
                            <a href="javascript:void(0);" data-bs-toggle="card-fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="card-text fw-medium">FullScreen Card</h6>
                            <p class="card-text mb-0">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words</p>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Read More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Header & Footer -->

        <!-- Mixins Utilities -->
        <div class="col-12">
            <h6 class="mb-3">Mixins Utilities:</h6>

            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card border border-success mb-3">
                        <div class="card-header bg-transparent border-bottom border-success">Header</div>
                        <div class="card-body text-success">
                            <h6 class="card-title fw-medium">Looking For Success?</h6>
                            <p class="card-text">If you are going to use a passage of Lorem Ipsum, you need
                                to be sure there isn't anything embarrassing hidden in the middle of text. All
                                the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as
                                necessary.</p>
                        </div>
                        <div class="card-footer bg-transparent border-top border-success">Footer</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mixins Utilities -->

        <!-- Text Alignment -->
        <div class="col-12">
            <h6 class="mb-3">Text Alignment:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                            </div>
                            <h6 class="card-title fw-medium">Where it come from</h6>
                            <p class="card-text">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card text-end custom">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                            </div>
                            <h6 class="card-title fw-medium">What is special?</h6>
                            <p class="card-text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4">
                    <div class="card text-center custom">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="avatar avatar-md">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                            <h6 class="card-title fw-medium">Why do we use it?</h6>
                            <p class="card-text">Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Text Alignment -->

        <!-- Using Utilities -->
        <div class="col-12">
            <h6 class="mb-3">Using Utilities:</h6>

            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card custom w-75">
                        <div class="card-header">
                            <div class="card-title">Using Width 75%</div>
                        </div> 
                        <div class="card-body">
                            <div class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Id nostrum omnis excepturi consequatur molestiae 
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="javascript:void(0);" class="btn btn-primary d-grid">Button</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card custom w-50">
                        <div class="card-header">
                            <div class="card-title">Using Width 50%</div>
                        </div> 
                        <div class="card-body">
                            <div class="card-text">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit 
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="javascript:void(0);" class="btn btn-primary d-grid">Button</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Using Utilities -->

        <!-- Navigation -->
        <div class="col-12">
            <h6 class="mb-3">Navigation:</h6>

            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card custom text-center">
                        <div class="card-header p-4 pt-3 ps-2">
                            <ul class="nav nav-tabs card-header-tabs ms-1">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="true" href="javascript:void(0);">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:void(0);">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Special title treatment</h6>
                            <p class="card-text">With supporting text below as a natural lead-in to
                                additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card text-center custom">
                        <div class="card-header">
                            <ul class="nav nav-pills card-header-pills ms-1">
                                <li class="nav-item">
                                    <a class="nav-link active" href="javascript:void(0);">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:void(0);">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Special title treatment</h6>
                            <p class="card-text">With supporting text below as a natural lead-in to
                                additional content.</p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation -->

        <!-- Image Caps -->
        <div class="col-12">
            <h6 class="mb-3">Image Caps:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Image caps are widely used in Blog's</h6>
                            <p class="card-text mb-3 text-muted">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings.</p>
                            <p class="card-text mb-0">
                                <small>Last updated 3 minsago</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Image caps are widely used in Blog's</h6>
                            <p class="card-text mb-3 text-muted">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound.</p>
                            <p class="card-text mb-0">
                                <small>Last updated 3 mins ago</small>
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img-bottom" alt="...">
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Image caps are widely used in Blog's</div>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-1 text-muted">This is a wider card with supporting text
                                below as a natural lead-in to additional content. This content is a little bit
                                longer.
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img rounded-0" alt="...">
                        <div class="card-footer">
                            <p class="card-text mb-0">
                                <small>Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/4.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-header">
                            <div class="card-title">Image caps are widely used in Blog's</div>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-1 text-muted">This is a wider card with supporting text
                                below as a natural lead-in to additional content. This content is a little bit
                                longer.
                            </p>
                        </div>
                        <div class="card-footer">
                            <p class="card-text mb-0">
                                <small>Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-body">
                            <h6 class="card-title fw-medium mb-1">Image caps are widely used in Blog's</h6>
                            <p class="card-text mb-1 text-muted">This is a wider card with supporting text
                                below as a natural lead-in to additional content. This content is a little bit
                                longer.
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/mountain/5.jpg') }}" class="card-img rounded-0" alt="...">
                        <div class="card-body">
                            <p class="card-text mb-0">
                                <small>Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Image caps are widely used in Blog's</div>
                        </div>
                        <div class="card-body">
                            <p class="card-text mb-1 text-muted">This is a wider card with supporting text
                                below as a natural lead-in to additional content. This content is a little bit
                                longer.
                            </p>
                        </div>
                        <div class="card-footer">
                            <p class="card-text mb-0">
                                <small>Last updated 3 mins ago</small>
                            </p>
                        </div>
                        <img src="{{ asset('assets/images/mountain/6.jpg') }}" class="card-img-bottom" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Caps -->

        <!-- Image Overlays -->
        <div class="col-12">
            <h6 class="mb-3">Image Overlays:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom overlay-card">
                        <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img" alt="...">
                        <div class="card-img-overlay d-flex flex-column p-0">
                            <div class="card-header">
                                <div class="card-title text-fixed-white">
                                    Image Overlays Are Awesome!
                                </div>
                            </div>
                            <div class="card-body text-fixed-white">
                                <div class="card-text mb-2">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even.</div>
                                <div class="card-text">Last updated 3 mins ago</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom overlay-card">
                        <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img" alt="...">
                        <div class="card-img-overlay d-flex flex-column p-0 over-content-bottom">
                            <div class="card-body text-fixed-white">
                                <div class="card-text text-fixed-white d-none d-sm-block">
                                    Image Overlays Are Awesome!
                                </div>
                                <div class="card-text mb-2">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even.</div>
                                <div class="card-text">Last updated 3 mins ago</div>
                            </div>
                            <div class="card-footer text-fixed-white">Last updated 3 mins ago</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom overlay-card">
                        <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img" alt="...">
                        <div class="card-img-overlay d-flex flex-column p-0">
                            <div class="card-header">
                                <div class="card-title text-fixed-white">
                                    Image Overlays Are Awesome!
                                </div>
                            </div>
                            <div class="card-body text-fixed-white">
                                <div class="card-text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even.</div>
                            </div>
                            <div class="card-footer text-fixed-white">Last updated 3 mins ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Overlays -->

        <!-- Horizontal Card -->
        <div class="col-12">
            <h6 class="mb-3">Horizontal Card:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="img-fluid rounded-start h-100 w-100" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-header">
                                    <div class="card-title">Horizontal Cards</div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-medium">Horizontal cards are awesome!</h6>
                                    <p class="card-text">This is a wider card with supporting text below as a natural .</p>
                                </div>
                                <div class="card-footer">
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-header">
                                    <div class="card-title">Horizontal Cards</div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-medium">Horizontal cards are awesome!</h6>
                                    <p class="card-text mb-3">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="img-fluid rounded-end h-100 w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h6 class="card-title fw-medium mb-2">Horizontal Cards</h6>
                                    <div class="card-title mb-3">Horizontal cards are awesome!</div>
                                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                </div>
                                <div class="card-footer">
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="img-fluid rounded-end h-100 w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Horizontal Card -->

        <!-- Background Colored Card -->
        <div class="col-12">
            <h6 class="mb-3">Background Colored Card:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card custom card-bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center w-100">
                                <div class="me-2">
                                    <span class="avatar avatar-rounded">
                                        <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="">
                                    <div class="fs-15 fw-medium">ChatGPT</div>
                                    <p class="mb-0 text-fixed-white op-7 fs-12">Finished by today</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="text-fixed-white"><i class="ti ti-dots-vertical"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom card-bg-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center w-100">
                                <div class="me-2">
                                    <span class="avatar avatar-rounded">
                                        <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="">
                                    <div class="fs-15 fw-medium">ChatGPT</div>
                                    <p class="mb-0 text-fixed-white op-7 fs-12">Finished by today</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="text-fixed-white"><i class="ti ti-dots-vertical"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom card-bg-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center w-100">
                                <div class="me-2">
                                    <span class="avatar avatar-rounded">
                                        <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                    </span>
                                </div>
                                <div class="">
                                    <div class="fs-15 fw-medium">ChatGPT</div>
                                    <p class="mb-0 text-fixed-white op-7 fs-12">Finished by today</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="javascript:void(0);" class="text-fixed-white"><i class="ti ti-dots-vertical"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Background Colored Card -->

        <!-- Colored Border Card -->
        <div class="col-12">
            <h6 class="mb-3">Colored Border Card:</h6>

            <div class="row gx-4">
                <div class="col-xl-4">
                    <div class="card border border-primary custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                Home Page Design
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-primary fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-primary-transparent">Framework</span>
                                <span class="badge bg-secondary-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border border-secondary custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                Landing Page Design
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-secondary fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-secondary-transparent">Framework</span>
                                <span class="badge bg-secondary-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border border-success custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                Update New Projeect
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-success fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-success-transparent">Framework</span>
                                <span class="badge bg-success-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border border-danger custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                New Project Discussion
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-danger fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-danger-transparent">Framework</span>
                                <span class="badge bg-danger-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border border-warning custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                Recent Project Testing
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-warning fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-warning-transparent">Framework</span>
                                <span class="badge bg-danger-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border border-info custom">
                        <div class="card-body">
                            <p class="fs-14 fw-medium mb-2 lh-1">
                                About Us Page Design
                                <a href="javascript:void(0);"><i class="ti ti-square-plus float-end text-info fs-18"></i></a>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge bg-info-transparent">Framework</span>
                                <span class="badge bg-danger-transparent">Angular</span>
                                <span class="badge bg-info-transparent">Php</span>
                            </div>
                            <div class="avatar-list-stacked">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                </span>
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Colored Border Card -->

        <!-- Card Groups With Footer -->
        <div class="col-12">
            <h6 class="mb-3">Card Groups With Footer:</h6>

            <div class="card-group">
                <div class="card custom">
                    <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title fw-medium">Writing is an art.</h6>
                        <p class="card-text">This is a wider card with supporting text below as
                            a
                            natural lead-in to additional content. This card has even longer
                            content
                            than the first to show that equal height action.</p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                </div>
                <div class="card custom">
                    <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title fw-medium">Delecious food is a blessing!</h6>
                        <p class="card-text">This is a wider card with supporting text below as
                            a
                            natural lead-in to additional content. This content is a little bit
                            longer.</p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                </div>
                <div class="card custom">
                    <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title fw-medium">Is office the best place to earn knowledge?</h6>
                        <p class="card-text">This card has supporting text below as a natural
                            lead-in to additional content.</p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Groups With Footer -->

        <!-- Grid Card -->
        <div class="col-12">
            <h6 class="mb-3">Grid Card:</h6>

            <div class="row row-cols-1 row-cols-md-4 g-4">
                <div class="col">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/1.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Research improves ability</h6>
                            <p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/2.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Morphology of a typical fruit.</h6>
                            <p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/3.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Most tropical areas are suitable</h6>
                            <p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card custom">
                        <img src="{{ asset('assets/images/mountain/4.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h6 class="card-title fw-medium">Are They seasonal fruits ?</h6>
                            <p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Grid Card -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection