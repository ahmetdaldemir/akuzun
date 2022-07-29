<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AKUZUN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<body class="antialiased">
<div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="container">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <form action="{{route('xml.save')}}" method="post">
                    @csrf
                    <input class="form-control" name="url" plecaholder="URL">
                    <input class="form-control" name="percent" plecaholder="YÜZDE">

                    <button class="btn btn-danger">Kaydet</button>
                </form>
            </div>

            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <table class="table table-responsive">
                    <?php foreach($xml as $item){ ?>
                    <tr>
                        <td><?=$item->url?></td>
                        <td><?=$item->percent?></td>
                        <td><a href="{{route('xml.parser',['id'=>$item->id])}}">Çalıştır</a></td>
                        <td><a href="{{asset('books.xml')}}" download="">İndir</a></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
</html>
