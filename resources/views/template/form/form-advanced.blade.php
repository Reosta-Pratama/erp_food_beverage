@extends('layouts.app', [
    'title' => 'Form Advanced'
])

@section('styles')

    <!-- Tagify CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/@yaireo/tagify/tagify.css') }}">

    <!-- Dragsort CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/@yaireo/dragsort/dragsort.css') }}">

    <!-- Telephone Input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

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
                    <li class="breadcrumb-item active" aria-current="page">Form Advanced</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row">
        <!-- Auto Complete -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Auto Complete</div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-4">
                            <label for="autoComplete" class="form-label">Search Results Of Food & Drinks Combo</label>
                            <input type="search" class="form-control" id="autoComplete" placeholder="Placeholder" spellcheck=false autocomplete="off" autocapitalize="off">
                        </div>
                        <div class="col-xl-4">
                            <label for="autoComplete-color" class="form-label">Advanced Search Results For Colors</label>
                            <input type="search" class="form-control" id="autoComplete-color" spellcheck=false autocomplete="off" autocapitalize="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Auto Complete -->

        <!-- Tagify JS -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tagify JS</div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <label class="form-label d-block">Basic Tagify</label>
                            <input name='basic' value='tag1, tag2' autofocus class="form-control">
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Tagify With Custom Suggestions</label>
                            <input name='input-custom-dropdown' class="form-control some_class_name" placeholder='write some tags' value='css, html, javascript'>
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Disabled User Input</label>
                            <input name='tags-disabled-user-input' placeholder='Select tags from the list' class="form-control">
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Drag & Sort</label>
                            <input name='drag-sort' class="form-control" value='tag 1, tag 2, tag 3, tag 4, tag 5, tag 6' >
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Tagify With Users List</label>
                            <input name='users-list-tags' value='abatisse2@nih.gov, Justinian Hattersley' class="form-control">
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Tagify Single-Value Select</label>
                            <input name='tags-select-mode' class='selectMode form-control' placeholder="Please select" >
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Readonly Tags</label>
                            <input name='tags4' class="form-control" readonly value='tag1, tag 2, another tag'>
                        </div>

                        <div class="col-xl-6">
                            <label class="form-label d-block">Readonly Mix</label>
                            <input name='tags-readonly-mix'  type="text" class='readonlyMix form-control' placeholder="Type something" value='[
                                {
                                    "value"    : "foo",
                                    "readonly" : true,
                                    "title"    : "read-only tag"
                                },
                                {
                                    "value"    : "bar"
                                },
                                {
                                    "value"    : "Locked tag",
                                    "readonly" : true,
                                    "title"    : "Another readonly tag"
                                }
                            ]'>
                        </div>

                        <div class="col-xl-12">
                            <label class="form-label d-block">Tagify With Mix Text & Tags</label>
                            <textarea name='mix' class="form-control">[[{"id":200, "value":"cartman", "title":"Eric Cartman"}]] and [[kyle]] do not know [[{"value":"homer simpson", "readonly":true}]] because he's a relic.</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tagify JS -->

        <!-- Telephone Input -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Telephone Input</div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <label for="phone" class="form-label d-block">Basic Telephone Input</label>
                            <input class="form-control" id="phone" type="tel">
                        </div>

                        <div class="col-xl-5">
                            <label for="phone-validation" class="form-label d-block">Telephone Input with valdation</label>
                            <input id="phone-validation" type="tel" class="form-control">
                            <button id="btn" type="button" class="btn btn-primary btn-wave telephone-input-btn">Validate</button>
                            <span id="valid-msg" class="hide">✓ Valid</span>
                            <span id="error-msg" class="hide"></span>
                        </div>

                        <div class="col-xl-4">
                            <label for="phone-only-countries" class="form-label d-block">Telephone Input With Only Countries</label>
                            <input id="phone-only-countries" type="tel" class="form-control">
                        </div>

                        <div class="col-xl-4">
                            <label for="phone-only-countries" class="form-label d-block">Telephone Input With Only Countries</label>
                            <form id="form">
                                <p id="message" class="mb-1"></p>
                                <input id="phone-hidden-input" type="tel" class="form-control">
                                <button type="submit" class="btn btn-primary btn-wave telephone-input-btn">Submit</button>
                            </form>
                        </div>

                        <div class="col-xl-4">
                            <label for="phone-existing-number" class="form-label d-block">Display Existing Number</label>
                            <input class="form-control" id="phone-existing-number" type="tel" value="+447733312345">
                        </div>
                        
                        <div class="col-xl-4 selected-dial-code-input">
                            <label for="phone-show-selected-dial-code" class="form-label d-block">With Selected Dial Code</label>
                            <input class="form-control" id="phone-show-selected-dial-code" type="tel">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Telephone Input -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Tagify JS -->
    <script src="{{ asset('assets/plugin/@yaireo/tagify/tagify.min.js') }}"></script>

    <!-- Dragsort JS -->
    <script src="{{ asset('assets/plugin/@yaireo/dragsort/dragsort.js') }}"></script>

    <!-- Telephone Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/auto-complete-custom.js', 
        'resources/assets/js/custom/tagify-custom.js',
        'resources/assets/js/custom/telephone-input-custom.js'
    ])

@endsection