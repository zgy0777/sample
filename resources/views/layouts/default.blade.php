<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Sample App') - Laravel 入门教程</title>
    {{--引入public对应的css--}}
    {{--本质上是sass先编译再写入到app.css中--}}
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
{{--头部--}}
@include('layouts._header')

<div class="container">
    @yield('content')

    {{--脚部--}}
    @include('layouts._footer')

</div>



</body>
</html>