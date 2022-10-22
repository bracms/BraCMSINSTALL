<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

</head>
<body class="antialiased font-sans">
<div class="md:flex min-h-screen">
    <div class="w-full md:w-1/2 bg-white flex items-center justify-center">
        <div class="max-w-sm m-8">
            <div class="text-black text-5xl md:text-15xl font-black">
                {{ $code }}
            </div>

            <div class="w-16 h-1 bg-purple-light my-3 md:my-6"></div>

            <div class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal">
                {{ $msg  }}
                @if(is_array($data))
                    @if($data['file'])
                        in file {{$data['file']}}
                    @endif
                    @if($data['line'])
                        at line {{$data['line']}}
                    @endif
                @endif
            </div>
            <div class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal">
                @if(is_array($data))
                    @foreach($data['trace'] as $trace)
                        <div>
                            {{ $trace['file'] }} - {{ $trace['line'] }}
                        </div>
                    @endforeach
                @endif
            </div>

            <a>
                <button class="bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-grey-light hover:border-grey rounded-lg">
                    {{ ('Go Home') }}
                </button>
                <button class="bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-grey-light hover:border-grey rounded-lg">
                    {{ ('Find Help') }}
                </button>
            </a>
        </div>
    </div>

    <div class="relative pb-full md:flex md:pb-0 md:min-h-screen w-full md:w-1/2">
        @yield('image')
    </div>
</div>
</body>
</html>
