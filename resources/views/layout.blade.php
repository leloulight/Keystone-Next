<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name=viewport content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen">

		<link rel="shortcut icon" href="/favicon.ico"/>

		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png"/>
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png"/>
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png"/>
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png"/>
		<meta name="apple-mobile-web-app-title" content="Keystone Next">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="apple-mobile-web-app-capable" content="yes">

		<link href="/apple-touch-startup-image-1536x2008.png"
          media="(device-width: 768px) and (device-height: 1024px)
                 and (-webkit-device-pixel-ratio: 2)
                 and (orientation: portrait)"
          rel="apple-touch-startup-image">
 
	    <!-- iPad retina landscape startup image -->
	    <link href="/apple-touch-startup-image-1496x2048.png"
	          media="(device-width: 768px) and (device-height: 1024px)
	                 and (-webkit-device-pixel-ratio: 2)
	                 and (orientation: landscape)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPad non-retina portrait startup image -->
	    <link href="/apple-touch-startup-image-768x1004.png"
	          media="(device-width: 768px) and (device-height: 1024px)
	                 and (-webkit-device-pixel-ratio: 1)
	                 and (orientation: portrait)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPad non-retina landscape startup image -->
	    <link href="/apple-touch-startup-image-748x1024.png"
	          media="(device-width: 768px) and (device-height: 1024px)
	                 and (-webkit-device-pixel-ratio: 1)
	                 and (orientation: landscape)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone 6 Plus portrait startup image -->
	    <link href="/apple-touch-startup-image-1242x2148.png"
	          media="(device-width: 414px) and (device-height: 736px)
	                 and (-webkit-device-pixel-ratio: 3)
	                 and (orientation: portrait)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone 6 Plus landscape startup image -->
	    <link href="/apple-touch-startup-image-1182x2208.png"
	          media="(device-width: 414px) and (device-height: 736px)
	                 and (-webkit-device-pixel-ratio: 3)
	                 and (orientation: landscape)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone 6 startup image -->
	    <link href="/apple-touch-startup-image-750x1294.png"
	          media="(device-width: 375px) and (device-height: 667px)
	                 and (-webkit-device-pixel-ratio: 2)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone 5 startup image -->
	    <link href="/apple-touch-startup-image-640x1096.png"
	          media="(device-width: 320px) and (device-height: 568px)
	                 and (-webkit-device-pixel-ratio: 2)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone < 5 retina startup image -->
	    <link href="/apple-touch-startup-image-640x920.png"
	          media="(device-width: 320px) and (device-height: 480px)
	                 and (-webkit-device-pixel-ratio: 2)"
	          rel="apple-touch-startup-image">
	 
	    <!-- iPhone < 5 non-retina startup image -->
	    <link href="/apple-touch-startup-image-320x460.png"
	          media="(device-width: 320px) and (device-height: 480px)
	                 and (-webkit-device-pixel-ratio: 1)"
	          rel="apple-touch-startup-image">

	    <title>@yield('title')</title>
	    
	    @yield('head')
	</head>
	<body @yield('body-attr')>
		@yield('body')
	</body>
</html>