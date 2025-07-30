@props(['url', 'title', 'icon', 'desc'])

<div>
    <a href="{{ route( $url ) }}" class="card bg-body-white hoverable card-xl-stretch mb-xl-8">
        <!--begin::Body-->
        <div class="card-body">
            <i class="{{ $icon }}">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
            <div class="text-gray-900 fw-bold fs-2 mb-2 mt-5">{{ $title }}</div>
            <div class="fw-semibold text-gray-400">{{ $desc }}</div>
        </div>
        <!--end::Body-->
    </a>
    <!--end::Statistics Widget 5-->
</div>