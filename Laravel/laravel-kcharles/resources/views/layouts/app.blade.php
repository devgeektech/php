<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>K Charles Haulage - World Freight and Logistics </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->

</head>
    <body>
    
  <!--  <a data-fancybox data-src="#agreePopup" href="javascript:;">
	Trigger the fancybox
</a>-->
    <input type="hidden" id="token" value="{{ csrf_token() }}">
	@if(Session::has('Agreecookie'))
	<div style="display: none;">
	@else
    <div style="display: none;" id="agreePopup">
	@endif
        <div class="row">
            <div class="col-md-8">
                <h1>@lang('inner-content.frontend.popup.heading')</h1>
                <p>@lang('inner-content.frontend.popup.content')</p>
            </div>
            <div class="col-md-4">
                <ul>
                    <li><a class="agreebtn" data-fancybox-close>@lang('inner-content.frontend.popup.agreebutton')</a></li>
                    <!--li><a class="cancelbtn">More information</a></li-->
                </ul>
            </div>
        </div>    
    </div>
    
    @include('includes.partials.demo')

    <div id="app" class="@yield('classes', '')">
        @include('includes.partials.logged-in-as')
        @include('frontend.includes.nav')
        @yield('slider')

        @include('includes.partials.messages')
        @yield('content')

        @include('frontend.includes.footer')
        @include('frontend.includes.modals')
    </div><!-- #app -->

    <!-- Scripts -->
    @stack('before-scripts')
	{!! script(mix('js/manifest.js').getAutoVersion('js/manifest.js')) !!}
	{!! script(mix('js/vendor.js').getAutoVersion('js/vendor.js')) !!}
	{!! script(mix('js/frontend.js').getAutoVersion('js/frontend.js')) !!}
    <script type="text/javascript" src="{{asset('js/jquery.fancybox.min.js').getAutoVersion('js/jquery.fancybox.min.js')}}"></script>
    @stack('after-scripts')

    @include('includes.partials.ga')
    @include('includes.partials.user-clicks')


    <script type="text/javascript">
        $(document).ready(function() {
            $("#agreePopup").fancybox().trigger('click');
        });
    </script>
	
    </body>
</html>