<x-app-layout>
<div class="container mt-5">
    <h2> Lista gatunków </h2>

    <table class="table table-striped">
        <tread>
            <tr>
                @foreach($columns as $column)
                    <th>{{ucfirst($column)}}</th>
                @endforeach
            </tr>
        </tread>
        <tbody>
            @foreach($genres as $genre)
                <tr>
                    @foreach($columns as $column)
                        <td>{{$genre->$column}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
    {{ $genres->links() }}

