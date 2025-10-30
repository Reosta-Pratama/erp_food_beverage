@extends('layouts.app', [
    'title' => 'Scrollspy'
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
                        <a href="javascript:void(0);">Advanced UI</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Scrollspy</li>
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
                    <div class="bd-example">
                        <div class="row">
                            <div class="col-md-2 col-12">
                                <div id="list-example" class="list-group">
                                    <a class="list-group-item list-group-item-action" href="#list-item-01">Item 1</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-02">Item 2</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-03">Item 3</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-04">Item 4</a>
                                </div>
                            </div>
                            <div class="col-md-10 col-12">
                                <div
                                    data-bs-spy="scroll"
                                    data-bs-target="#list-example"
                                    data-bs-smooth-scroll="true"
                                    class="scrollspy-example-3"
                                    tabindex="0">
                                    <div id="list-item-01">
                                        <h6 class="fw-medium">Item 1</h6>
                                        <p class="text-muted">
                                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex
                                            sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla
                                            lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer
                                            nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos. Lorem ipsum dolor sit amet
                                            consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem
                                            placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum
                                            egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit
                                            semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra
                                            inceptos himenaeos. Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                                        </p>
                                    </div>
                                    <div id="list-item-02">
                                        <h6 class="fw-medium">Item 2</h6>
                                        <p class="text-muted">
                                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex
                                            sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla
                                            lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer
                                            nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos. Lorem ipsum dolor sit amet
                                            consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem
                                            placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum
                                            egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit
                                            semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra
                                            inceptos himenaeos. Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                                        </p>
                                    </div>
                                    <div id="list-item-03">
                                        <h6 class="fw-medium">Item 3</h6>
                                        <p class="text-muted">
                                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex
                                            sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla
                                            lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer
                                            nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos. Lorem ipsum dolor sit amet
                                            consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem
                                            placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum
                                            egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit
                                            semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra
                                            inceptos himenaeos. Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                                        </p>
                                    </div>
                                    <div id="list-item-04">
                                        <h6 class="fw-medium">Item 4</h6>
                                        <p class="text-muted">
                                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex
                                            sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla
                                            lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer
                                            nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos. Lorem ipsum dolor sit amet
                                            consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem
                                            placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum
                                            egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit
                                            semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra
                                            inceptos himenaeos. Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Nested -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Nested</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <nav
                                id="navbar-example3"
                                class="navbar bg-light flex-column align-items-stretch p-3">
                                <nav class="nav nav-pills flex-column">
                                    <a class="nav-link" href="#item-1">Item 1</a>
                                    <nav class="nav nav-pills flex-column">
                                        <a class="nav-link ms-3 my-1" href="#item-1-1">Item 1-1</a>
                                        <a class="nav-link ms-3 my-1" href="#item-1-2">Item 1-2</a>
                                    </nav>
                                    <a class="nav-link" href="#item-2">Item 2</a>
                                    <a class="nav-link" href="#item-3">Item 3</a>
                                    <nav class="nav nav-pills flex-column">
                                        <a class="nav-link ms-3 my-1" href="#item-3-1">Item 3-1</a>
                                        <a class="nav-link ms-3 my-1" href="#item-3-2">Item 3-2</a>
                                    </nav>
                                </nav>
                            </nav>
                        </div>
                        <div class="col-md-9 col-sm-8">
                            <div
                                data-bs-spy="scroll"
                                data-bs-target="#navbar-example3"
                                data-bs-smooth-scroll="true"
                                tabindex="0"
                                class="scrollspy-example-2">
                                <div id="item-1">
                                    <h6 class="fw-medium">Item 1</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        Asperiores explicabo in delectus nostrum aut ab quasi placeat facilis? Laborum
                                        corporis eaque ipsum laboriosam animi possimus.</p>
                                </div>
                                <div id="item-1-1">
                                    <h6 class="fw-medium">Item 1-1</h6>
                                    <p class="text-muted">Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                                        Placeat minus distinctio itaque odit magnam voluptate quos ipsam ab provident!
                                        Facere minus aperiam non architecto sequi, temporibus aspernatur harum
                                        consequatur, laboriosam nam ratione adipisci? Doloremque sed ducimus aliquid
                                        dicta beatae! Quasi voluptas aliquam aliquid error reiciendis enim! Iure
                                        obcaecati consequatur harum suscipit delectus.
                                    </p>
                                </div>
                                <div id="item-1-2">
                                    <h6 class="fw-medium">Item 1-2</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        Tempore eius consectetur quae modi iste voluptatibus a quidem amet ea corporis
                                        neque non quasi nesciunt sunt numquam minima maiores eveniet ratione soluta
                                        temporibus, quam harum nostrum. Laudantium repellat, dolores blanditiis iusto
                                        tempora corrupti. Distinctio, nesciunt. Tenetur sapiente cumque, totam veniam
                                        repellat alias quasi, beatae eveniet quas eos, ea aperiam!
                                    </p>
                                </div>
                                <div id="item-2">
                                    <h6 class="fw-medium">Item 2</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                        Eius, itaque debitis. Numquam facere sunt adipisci dolores ratione quo magni.
                                        Non, officiis minima deserunt consequatur, repellendus nihil laudantium aperiam
                                        laborum eaque animi maxime porro saepe nisi quos. Corporis hic tempore illo
                                        reiciendis autem, necessitatibus debitis sed molestias.</p>
                                </div>
                                <div id="item-3">
                                    <h6 class="fw-medium">Item 3</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                                        Fuga voluptate sequi exercitationem voluptatem, commodi dicta nostrum atque,
                                        praesentium consequatur eos at vero animi neque deleniti ipsa. Aliquid facere
                                        saepe pariatur porro nihil blanditiis recusandae dolor fuga? Iusto et omnis
                                        neque doloremque, cum modi officia facilis placeat repellendus obcaecati
                                        mollitia! Id aperiam officiis vitae. Fugit quo id veritatis commodi maiores
                                        numquam nostrum necessitatibus eaque, quia exercitationem distinctio ipsa eum,
                                        nihil atque perferendis dicta, mollitia sed dignissimos incidunt voluptas ab
                                        tempore laborum? Fugiat, deserunt. Harum, repellat praesentium! Fuga!</p>
                                </div>
                                <div id="item-3-1">
                                    <h6 class="fw-medium">Item 3-1</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                        Numquam non deleniti saepe voluptate, est praesentium ducimus sapiente aut
                                        dignissimos voluptas blanditiis reiciendis earum accusantium id ex!</p>
                                </div>
                                <div id="item-3-2">
                                    <h6 class="fw-medium">Item 3-2</h6>
                                    <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        Quo, est. Blanditiis cupiditate voluptate obcaecati eligendi iusto amet.
                                        Cupiditate laborum, itaque laboriosam culpa sunt eligendi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Nested -->

        <!-- Navbar -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Navbar</div>
                </div>
                <div class="card-body">
                    <nav id="navbar-example2" class="navbar navbar-light bg-light px-3 mb-3">
                        <ul class="nav nav-pills d-sm-flex d-block">
                            <li class="nav-item">
                                <a class="nav-link" href="#scrollspyHeading1">First</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#scrollspyHeading2">Second</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    href="javascript:void(0);"
                                    role="button"
                                    aria-expanded="false">Dropdown</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#scrollspyHeading3">Third</a></li>
                                    <li><a class="dropdown-item" href="#scrollspyHeading4">Fourth</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#scrollspyHeading5">Fifth</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <div
                        data-bs-spy="scroll"
                        data-bs-target="#navbar-example2"
                        data-bs-offset="0"
                        class="scrollspy-example bg-light p-3 rounded-2"
                        data-bs-root-margin="0px 0px -40%"
                        data-bs-smooth-scroll="true"
                        tabindex="0">
                        <h6 class="fw-medium" id="scrollspyHeading1">First heading</h6>
                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo assumenda
                            distinctio magni, reprehenderit sit deleniti excepturi! Tempore magni adipisci
                            iusto sit illum vero veniam harum quas sunt!Lorem ipsum dolor sit amet
                            consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat,
                            placeat nemo assumenda distinctio magni, reprehenderit sit deleniti excepturi!
                            Tempore magni adipisci iusto sit illum vero veniam harum quas suntLorem ipsum
                            dolor sit amet consectetur adipisicing elit. Quidem culpa alias, illum vero
                            cupiditate fugiat, placeat nemo assumenda distinctio magni,Lorem ipsum dolor sit
                            amet consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate
                            fugiat, placeat nemo assumenda distinctio magni, reprehenderit sit deleniti
                            excepturi! Tempore magni adipisci iusto sit illum vero veniam harum quas
                            sunt!Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem culpa
                            alias, illum vero cupiditate fugiat, placeat nemo assumenda distinctio magni,
                            reprehenderit sit deleniti excepturi! Tempore magni adipisci iusto sit illum
                            vero veniam harum quas suntLorem ipsum dolor sit amet consectetur adipisicing
                            elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo assumenda
                            distinctio magni,Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                            culpa alias, illum vero cupiditate fugiat, placeat nemo assumenda distinctio
                            magni, reprehenderit sit deleniti excepturi! Tempore magni adipisci iusto sit
                            illum vero veniam harum quas sunt!Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo
                            assumenda distinctio magni, reprehenderit sit deleniti excepturi! Tempore magni
                            adipisci iusto sit illum vero veniam harum quas suntLorem ipsum dolor sit amet
                            consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat,
                            placeat nemo assumenda distinctio magni,Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo
                            assumenda distinctio magni, reprehenderit sit deleniti excepturi! Tempore magni
                            adipisci iusto sit illum vero veniam harum quas sunt!Lorem ipsum dolor sit amet
                            consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat,
                            placeat nemo assumenda distinctio magni, reprehenderit sit deleniti excepturi!
                            Tempore magni adipisci iusto sit illum vero veniam harum quas suntLorem ipsum
                            dolor sit amet consectetur adipisicing elit. Quidem culpa alias, illum vero
                            cupiditate fugiat, placeat nemo assumenda distinctio magni,Lorem ipsum dolor sit
                            amet consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate
                            fugiat, placeat nemo assumenda distinctio magni, reprehenderit sit deleniti
                            excepturi! Tempore magni adipisci iusto sit illum vero veniam harum quas
                            sunt!Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem culpa
                            alias, illum vero cupiditate fugiat, placeat nemo assumenda distinctio magni,
                            reprehenderit sit deleniti excepturi! Tempore magni adipisci iusto sit illum
                            vero veniam harum quas suntLorem ipsum dolor sit amet consectetur adipisicing
                            elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo assumenda
                            distinctio magni,Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
                            culpa alias, illum vero cupiditate fugiat, placeat nemo assumenda distinctio
                            magni, reprehenderit sit deleniti excepturi! Tempore magni adipisci iusto sit
                            illum vero veniam harum quas sunt!Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo
                            assumenda distinctio magni, reprehenderit sit deleniti excepturi! Tempore magni
                            adipisci iusto sit illum vero veniam harum quas suntLorem ipsum dolor sit amet
                            consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat,
                            placeat nemo assumenda distinctio magni,Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat, placeat nemo
                            assumenda distinctio magni, reprehenderit sit deleniti excepturi! Tempore magni
                            adipisci iusto sit illum vero veniam harum quas sunt!Lorem ipsum dolor sit amet
                            consectetur adipisicing elit. Quidem culpa alias, illum vero cupiditate fugiat,
                            placeat nemo assumenda distinctio magni, reprehenderit sit deleniti excepturi!
                            Tempore magni adipisci iusto sit illum vero veniam harum quas suntLorem ipsum
                            dolor sit amet consectetur adipisicing elit. Quidem culpa alias, illum vero
                            cupiditate fugiat, placeat nemo assumenda distinctio magni,
                        </p>
                        <h6 class="fw-medium" id="scrollspyHeading2">Second heading</h6>
                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Aliquam veniam ullam perspiciatis ut fugit atque iure, quae animi ex tempore.
                            Ducimus illum fugit sapiente quisquam!Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Quidem culpa alias,Lorem ipsum dolor sit amet consectetur
                            adipisicing elit. Aliquam veniam ullam perspiciatis ut fugit atque iure, quae
                            animi ex tempore. Ducimus illum fugit sapiente quisquam!Lorem ipsum dolor sit
                            amet consectetur adipisicing elit. Quidem culpa alias,Lorem ipsum dolor sit amet
                            consectetur adipisicing elit. Aliquam veniam ullam perspiciatis ut fugit atque
                            iure, quae animi ex tempore. Ducimus illum fugit sapiente quisquam!Lorem ipsum
                            dolor sit amet consectetur adipisicing elit. Quidem culpa alias,Lorem ipsum
                            dolor sit amet consectetur adipisicing elit. Aliquam veniam ullam perspiciatis
                            ut fugit atque iure, quae animi ex tempore. Ducimus illum fugit sapiente
                            quisquam!Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem culpa
                            alias, illum vero cupiditate fugiat, placeat nemo assumenda distinctio magni
                        </p>
                        <h6 class="fw-medium" id="scrollspyHeading3">Third heading</h6>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                            Eveniet nobis et quaerat asperiores fugit dignissimos rerum qui minus vitae
                            nesciunt nisi aspernatur aperiam quidem magnam, cumque repudiandae quod aliquid
                            quo?
                        </p>
                        <h6 class="fw-medium" id="scrollspyHeading4">Fourth heading</h6>
                        <p class="text-muted">Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                            Maiores tempora pariatur modi corporis aspernatur eveniet?</p>
                        <h6 class="fw-medium" id="scrollspyHeading5">Fifth heading</h6>
                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Ullam voluptates consequuntur ipsum eos, magni vitae tempore suscipit excepturi
                            blanditiis!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar -->

        <!-- Simple Anchor -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Simple Anchor</div>
                </div>
                <div class="card-body">
                    <div class="bd-example">
                        <div class="row">
                            <div class="col-md-2 col-12">
                                <div
                                    id="simple-list-example"
                                    class="d-flex flex-column gap-2 simple-list-example-scrollspy text-center">
                                    <a class="p-2 rounded" href="#simple-list-item-1">Item 1</a>
                                    <a class="p-2 rounded" href="#simple-list-item-2">Item 2</a>
                                    <a class="p-2 rounded" href="#simple-list-item-3">Item 3</a>
                                    <a class="p-2 rounded" href="#simple-list-item-4">Item 4</a>
                                    <a class="p-2 rounded" href="#simple-list-item-5">Item 5</a>
                                </div>
                            </div>
                            <div class="col-md-10 col-12">
                                <div
                                    data-bs-spy="scroll"
                                    data-bs-target="#simple-list-example"
                                    data-bs-offset="0"
                                    data-bs-smooth-scroll="true"
                                    class="scrollspy-example-4"
                                    tabindex="0">
                                    <div id="simple-list-item-1">
                                        <h6 class="fw-medium">Item 1</h6>
                                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos. Lorem
                                            ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien
                                            vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis.
                                            Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec
                                            metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc
                                            posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos.</p>
                                    </div>

                                    <div id="simple-list-item-2">
                                        <h6 class="fw-medium">Item 2</h6>
                                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos. Lorem
                                            ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien
                                            vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis.
                                            Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec
                                            metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc
                                            posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos.</p>
                                    </div>

                                    <div id="simple-list-item-3">
                                        <h6 class="fw-medium">Item 3</h6>
                                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos. Lorem
                                            ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien
                                            vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis.
                                            Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec
                                            metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc
                                            posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos.</p>
                                    </div>

                                    <div id="simple-list-item-4">
                                        <h6 class="fw-medium">Item 4</h6>
                                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos. Lorem
                                            ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien
                                            vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis.
                                            Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec
                                            metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc
                                            posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos.</p>
                                    </div>

                                    <div id="simple-list-item-5">
                                        <h6 class="fw-medium">Item 5</h6>
                                        <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipiscing elit.
                                            Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi
                                            pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.
                                            Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl
                                            malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent
                                            taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos. Lorem
                                            ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien
                                            vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis.
                                            Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec
                                            metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc
                                            posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora
                                            torquent per conubia nostra inceptos himenaeos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Simple Anchor -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection