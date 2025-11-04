@extends('layouts.app', [
    'title' => 'File Uploads'
])

@section('styles')

    <!-- Filespond CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/filepond-plugin-image-edit/filepond-plugin-image-edit.min.css') }}">
    
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/dropzone/dropzone.css') }}">

@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">File Uploads</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row">
        <!-- Default -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Default file input example</label>
                        <input class="form-control" type="file" id="formFile">
                    </div>

                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Multiple files input example</label>
                        <input class="form-control" type="file" id="formFileMultiple" multiple="">
                    </div>

                    <div class="mb-3">
                        <label for="formFileDisabled" class="form-label">Disabled file input example</label>
                        <input class="form-control" type="file" id="formFileDisabled" disabled="">
                    </div>

                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Small file input example</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file">
                    </div>

                    <div>
                        <label for="formFileLg" class="form-label">Large file input example</label>
                        <input class="form-control form-control-lg" id="formFileLg" type="file">
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Filepond JS: Single Upload -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Filepond JS: Single Upload</div>
                </div>
                <div class="card-body">
                    <input
                        type="file"
                        class="single-fileupload"
                        name="filepond"
                        accept="image/png, image/jpeg, image/gif">
                </div>
            </div>
        </div>
        <!-- Filepond JS: Single Upload -->

        <!-- Filepond JS: Multiple Upload -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Filepond JS: Multiple Upload</div>
                </div>
                <div class="card-body">
                    <input
                        type="file"
                        class="multiple-filepond"
                        name="filepond"
                        multiple="multiple"
                        data-allow-reorder="true"
                        data-max-file-size="3MB"
                        data-max-files="6">
                </div>
            </div>
        </div>
        <!-- Filepond JS: Multiple Upload -->

        <!-- Dropzone JS -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropzone JS</div>
                </div>
                <div class="card-body">
                    <form
                        data-single="true"
                        method="post"
                        action="https://httpbin.org/post"
                        class="dropzone"></form>
                </div>
            </div>
        </div>
        <!-- Dropzone JS -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Filepond JS -->
    <script src="{{ asset('assets/plugin/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-edit/filepond-plugin-image-edit.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/filepond-plugin-image-transform/filepond-plugin-image-transform.min.js') }}"></script>

    <!-- Dropzone JS -->
    <script src="{{ asset('assets/plugin/dropzone/dropzone-min.js') }}"></script>
    
    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-file-upload-custom.js'
    ])

@endsection