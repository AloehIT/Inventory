<meta charset="utf-8">
<title>@yield('title') {{{ $perusahaan->value ?? '' }}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
<meta content="KantraWibawa" name="author">

<!-- App favicon -->
<link rel="shortcut icon" href="{{ URL::to('assets/icon/icon.png')}}">

<!-- App css -->
<link href="{{ URL::to('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::to('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
<link href="{{ URL::to('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">


<!-- Data Table -->
<link href="{{ URL::to('assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::to('assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::to('assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::to('assets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="{{URL::to('assets/css/vendor/simplemde.min.css')}}" rel="stylesheet" type="text/css" />


<style>
    trix-toolbar [data-trix-button-group="file-tools"] {
        display: none;
    }

    .trix-button--icon-link {
        display: none;
    }
</style>

<style>
    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }
    .custom-file-input::before {
        content: 'SELECT FILE';
        display: inline-block;
        background: linear-gradient(top, #f9f9f9, #e3e3e3);
        border-radius: 3px;
        padding: 5px 13px;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        font-weight: 500;
        font-size: 10pt;
        color: white;
        text-align: center;
    }
    .custom-file-input:hover::before {
        border-color: black;
    }
    .custom-file-input:active::before {
        background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
    }

</style>
@laravelPWA
