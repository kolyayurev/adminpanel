<footer class="container-fluid">
    <hr class="border border-2">
    <div class="d-flex justify-content-end">
        @can('view_dev')
            @php
                $version = AdminPanel::getVersion();
                $branch  = AdminPanel::getBranch();
            @endphp
            @if (!empty($branch))
                {{ $branch }}
            @endif

            @if (!empty($version))
            - {{ $version }}
            @endif
        @endif
    </div>
</footer>
