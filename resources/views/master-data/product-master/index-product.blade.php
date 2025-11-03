<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container p-4 mx-auto">
        <div class="overflow-x-auto">

            {{-- Alert success / error --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-500">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-500">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form pencarian --}}
            <form method="GET" action="{{ route('product-index') }}" class="mb-4 flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                    class="w-1/4 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit"
                    class="ml-2 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Cari
                </button>
            </form>

            {{-- Tombol tambah data --}}
            <a href="{{ route('product-create') }}">
                <button
                    class="px-6 py-3 mb-4 text-white bg-green-500 border border-green-500 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Add Product Data
                </button>
            </a>

            <a href="{{ route('product-export-excel') }}">
                <button
                    class="px-6 py-3 mb-4 text-white bg-green-500 border border-green-500 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Download Excel
                </button>
            </a>

            <a href="{{ route('product-export-pdf') }}">
                <button
                    class="px-6 py-3 mb-4 text-white bg-green-500 border border-green-500 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Download PDF
                </button>
            </a>

            <a href="{{ route('product.export.jpg') }}">
                <button
                    class="px-6 py-3 mb-4 text-white bg-green-500 border border-green-500 rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Download Jpg
                </button>
            </a>

            {{-- Tabel data produk --}}
            <table class="min-w-full border border-collapse border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">#</th>

                        {{-- Product Name --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'product_name',
    'direction' => (request('sort') == 'product_name' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Product Name
                                @if (request('sort') == 'product_name')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        {{-- Unit --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'unit',
    'direction' => (request('sort') == 'unit' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Unit
                                @if (request('sort') == 'unit')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        {{-- Type --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'type',
    'direction' => (request('sort') == 'type' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Type
                                @if (request('sort') == 'type')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        {{-- Information --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'information',
    'direction' => (request('sort') == 'information' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Information
                                @if (request('sort') == 'information')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        {{-- Qty --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'qty',
    'direction' => (request('sort') == 'qty' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Qty
                                @if (request('sort') == 'qty')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        {{-- Producer --}}
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">
                            <a href="{{ route('product-index', [
    'sort' => 'producer',
    'direction' => (request('sort') == 'producer' && request('direction') == 'asc') ? 'desc' : 'asc',
    'search' => request('search')
]) }}" class="hover:underline">
                                Producer
                                @if (request('sort') == 'producer')
                                    {!! request('direction') == 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Jika tidak ada data --}}
                    @if ($data->count() == 0)
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500 font-semibold">
                                Produk tidak ditemukan.
                            </td>
                        </tr>
                    @endif

                    {{-- Loop data produk --}}
                    @foreach ($data as $item)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="px-4 py-2 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <a href="{{ route('product-detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $item->product_name }}
                                </a>
                            </td>
                            <td class="px-4 py-2 border border-gray-200">{{ $item->unit }}</td>
                            <td class="px-4 py-2 border border-gray-200">{{ $item->type }}</td>
                            <td class="px-4 py-2 border border-gray-200">{{ $item->information }}</td>
                            <td class="px-4 py-2 border border-gray-200">{{ $item->qty }}</td>
                            <td class="px-4 py-2 border border-gray-200">{{ $item->producer }}</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <a href="{{ route('product-edit', $item->id) }}"
                                    class="px-2 text-blue-600 hover:text-blue-800">Edit</a>
                                <button class="px-2 text-red-600 hover:text-red-800"
                                    onclick="confirmDelete('{{ route('product-delete', $item->id) }}')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $data->appends([
    'search' => request('search'),
    'sort' => request('sort'),
    'direction' => request('direction')
])->links() }}
            </div>
        </div>
    </div>

    {{-- Script konfirmasi hapus --}}
    <script>
        function confirmDelete(deleteUrl) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10B981'
                                                                        })
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#EF4444'
                                                                        })
            @endif
    </script>

    </script>
</x-app-layout>