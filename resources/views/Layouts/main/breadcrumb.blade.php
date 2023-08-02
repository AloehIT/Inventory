@inject('carbon', 'Carbon\Carbon')
@php
    $today = $carbon::now()->isoFormat('dddd, D MMMM Y');
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>
            <p class="page-title text-dark" style="font-weight: 400; font-size: 14px;"><i class="uil-calender"></i> {{ $today }}</p>
        </div>
    </div>
</div>
