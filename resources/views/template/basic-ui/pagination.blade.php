@extends('layouts.app', [
    'title' => 'Pagination'
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
                    <li class="breadcrumb-item active" aria-current="page">Pagination</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Default Pagination -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default Pagination</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0);">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Default Pagination -->

        <!-- Pagination With Icon -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pagination With Icon</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                    <span aria-hidden="true">
                                        <i class="ti ti-chevron-left"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);" aria-label="Next">
                                    <span aria-hidden="true">
                                        <i class="ti ti-chevron-right"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Pagination With Icon -->
    </div>

    <div class="row gx-4">
        <!-- Active & Disabled -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Active & Disabled</div>
                </div>
                <div class="card-body">
                    <nav aria-label="...">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Active & Disabled -->

        <!-- Style 1 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 1</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation" class="pagination-style-1">
                        <ul class="pagination mb-0 flex-wrap">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="ti ti-chevron-left align-middle"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="ti ti-dots"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">21</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="ti ti-chevron-right align-middle"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 1 -->

        <!-- Style 2 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 2</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation" class="pagination-style-2">
                        <ul class="pagination mb-0 flex-wrap">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0);">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">17</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link text-primary" href="javascript:void(0);">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 2 -->
    </div>

    <div class="row gx-4">
        <!-- Style 3 -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 3</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation" class="pagination-style-3">
                        <ul class="pagination mb-0 flex-wrap">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0);">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="ti ti-dots"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">16</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link text-primary" href="javascript:void(0);">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 3 -->

        <!-- Style 4 -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 4</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation" class="pagination-style-4">
                        <ul class="pagination mb-0 flex-wrap">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0);">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">
                                    <i class="ti ti-dots"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">16</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">17</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link text-primary" href="javascript:void(0);">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 4 -->
    </div>

    <div class="row gx-4">
        <!-- Sizing -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Sizing</div>
                </div>
                <div class="card-body d-flex flex-wrap justify-content-between gap-2">
                    <nav aria-label="...">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                        </ul>
                    </nav>
                    <nav aria-label="...">
                        <ul class="pagination mb-0">
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                        </ul>
                    </nav>
                    <nav aria-label="...">
                        <ul class="pagination pagination-lg mb-0">
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Sizing -->

        <!-- Position -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Position</div>
                </div>
                <div class="card-body">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end mb-0">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Position -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection