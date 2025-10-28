@extends('layouts.app', [
    'title' => 'Image Figures'
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
                    <li class="breadcrumb-item active" aria-current="page">Images & Figures</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Responsive Image -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Responsive Image</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code> .img-fluid </code>class to the img tag to get responsive image.</p>
                    <div class="text-center">
                        <img src="{{ asset('assets/images/200x200/1.jpg') }}" class="img-fluid" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Responsive Image -->

        <!-- Image With Radius -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image With Radius</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>.rounded</code> class along with <code>.img-fluid</code> to get border radius.</p>
                    <div class="text-center">
                        <img src="{{ asset('assets/images/200x200/2.jpg') }}" class="img-fluid rounded" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Image With Radius -->
    </div>

    <div class="row gx-4">
        <!-- Rounded Image -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Image</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>.rounded-pill</code> class to <code>img</code> tag to get rounded image.</p>
                    <div class="text-center">
                        <img src="{{ asset('assets/images/200x200/3.jpg') }}" class="img-fluid rounded-pill" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Image -->

        <!-- Image Left Align -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image Left Align</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>.float-start</code> class to <code>img</code> tag to get left align image.</p>
                    <img class="rounded float-start" src="{{ asset('assets/images/200x200/4.jpg') }}" alt="...">
                </div>
            </div>
        </div>
        <!-- Image Left Align -->
    </div>

    <div class="row gx-4">
        <!-- Image Center Align -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image Center Align</div>
                </div>
                <div class="card-body">
                    <img class="rounded mx-auto d-block" src="{{ asset('assets/images/200x200/5.jpg') }}" alt="...">
                </div>
            </div>
        </div>
        <!-- Image Center Align -->

        <!-- Image Right Align -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image Right Align</div>
                </div>
                <div class="card-body">
                    <img class="rounded float-end" src="{{ asset('assets/images/200x200/6.jpg') }}" alt="...">
                </div>
            </div>
        </div>
        <!-- Image Right Align -->
    </div>

    <div class="row gx-4">
        <!-- Figures -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Figures</div>
                </div>
                <div class="card-body d-flex justify-content-between gap-2">
                    <figure class="figure">
                        <img class="bd-placeholder-img figure-img img-fluid rounded card-img" src="{{ asset('assets/images/200x200/7.jpg') }}" alt="...">
                        <figcaption class="figure-caption mt-2">A caption for the above image.
                        </figcaption>
                    </figure>
                    <figure class="figure float-end">
                        <img class="bd-placeholder-img figure-img img-fluid rounded card-img" src="{{ asset('assets/images/200x200/8.jpg') }}" alt="...">
                        <figcaption class="figure-caption text-end mt-2">A caption for the above image.
                        </figcaption>
                    </figure>
                </div>
            </div>
        </div>
        <!-- Figures -->

        <!-- Image Thumbnail -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image Thumbnail</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code> .img-thumbnail </code>to give an image a rounded 1px border.</p>
                    <div class="text-center">
                        <img src="{{ asset('assets/images/200x200/9.jpg') }}" class="img-thumbnail" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Thumbnail -->

        <!-- Rounded Thumbnail -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Thumbnail</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code> .rounded-pill </code>along with <code> .img-thummbnail </code> to get radius.</p>
                    <div class="text-center">
                        <img src="{{ asset('assets/images/200x200/10.jpg') }}" class="img-thumbnail rounded-pill" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Thumbnail -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection