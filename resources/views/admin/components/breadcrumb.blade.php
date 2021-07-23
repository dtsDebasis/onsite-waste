<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{isset($pageHeading)?$pageHeading:''}}</h4>

            <div class="page-title-right">
                <!-- <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                    <li class="breadcrumb-item active">Roles &amp; Permission</li>
                </ol> -->
                @if(isset($breadcrumb))
                <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                @php ($breadcrumbLimit = count($breadcrumb))
                @php ($currentBreadcrumb = 1)
                @foreach($breadcrumb as $key => $val)
                <li class="breadcrumb-item">
                    @if($currentBreadcrumb != $breadcrumbLimit)
                    <a href="{{ $key }}">{{ $val }}</a>
                    @else
                    {{ $val }}
                    @endif
                    @php ($currentBreadcrumb++)
                </li>
                @endforeach
                </ol>
                @endif
            </div>

        </div>
    </div>
</div>