@if(session('status'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-800 rounded">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-800 rounded">
        <ul class="list-disc ms-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
