<!-- Required Js -->
<script src="{{ URL::asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ URL::asset('assets/js/pcoded.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
       var toastElList = [].slice.call(document.querySelectorAll('.toast'));
       toastElList.forEach(function (toastEl) {
          var toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
          });
          toast.show();
       });
    });
</script>

@if (env('APP_DARK_LAYOUT') == 'default')
<script>
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        dark_layout = 'true';
    } else {
        dark_layout = 'false';
    }
    layout_change_default();
    if (dark_layout == 'true') {
        layout_change('dark');
    } else {
        layout_change('light');
    }
</script>
@endif

@if (env('APP_DARK_LAYOUT') != 'default')
    @if (env('APP_DARK_LAYOUT') == 'true')
        <script>
            layout_change('dark');
        </script>
    @endif
    @if (env('APP_DARK_LAYOUT') == false)
        <script>
            layout_change('light');
        </script>
    @endif
@endif




@if (env('APP_BOX_CONTAINER') == false)
    <script>
        change_box_container('true');
    </script>
@endif

@if (env('APP_BOX_CONTAINER') == false)
    <script>
        change_box_container('false');
    </script>
@endif

@if (env('APP_CAPTION_SHOW') == 'true')
    <script>
        layout_caption_change('true');
    </script>
@endif

@if (env('APP_CAPTION_SHOW') == false)
    <script>
        layout_caption_change('false');
    </script>
@endif

@if (env('APP_RTL_LAYOUT') == 'true')
    <script>
        layout_rtl_change('true');
    </script>
@endif

@if (env('APP_RTL_LAYOUT') == false)
    <script>
        layout_rtl_change('false');
    </script>
@endif

@if (env('APP_PRESET_THEME') != '')
    <script>
        preset_change("{{env('APP_PRESET_THEME')}}");
    </script>
@endif
