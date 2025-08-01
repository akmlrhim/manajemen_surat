@extends('layouts.main')

@section('content')
  <div class="flex flex-col md:flex-row md:items-center md:justify-between sm:ml-6 mb-3 gap-2">
    <a href="{{ route('surat-masuk.create') }}"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-3 py-2 tracking-wide focus:outline-none capitalize w-fit">
      Tambah Data
    </a>

    <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
      <input type="text" name="search" placeholder="Masukkan kata kunci.."
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full md:w-64 p-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 font-medium"
        value="{{ request('search') }}" autocomplete="off">
      <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2">
        Cari
      </button>
    </form>
  </div>

  <div class="relative rounded-md shadow-md overflow-hidden sm:ml-6">
    <div class="overflow-x-auto">
      <table class="w-full text-xs md:text-sm text-left rtl:text-right text-black dark:text-gray-400">
        <thead class="text-black uppercase bg-white">
          <tr class="border-b-2 text-xs border-gray-200">
            <th scope="col" class="px-6 py-3">No.</th>
            <th scope="col" class="px-6 py-3">Perihal</th>
            <th scope="col" class="px-6 py-3">Asal Surat</th>
            <th scope="col" class="px-6 py-3">No Surat</th>
            <th scope="col" class="px-6 py-3">Tgl diterima</th>
            <th scope="col" class="px-6 py-3">Tgl surat</th>
            <th scope="col" class="px-6 py-3">dokumen</th>
            <th scope="col" class="px-6 py-3">Telaah staf</th>
            <th scope="col" class="px-6 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($suratMasuk as $row)
            <tr class="bg-white border-b-2 border-gray-200">
              <td class="px-6 py-3">
                {{ method_exists($suratMasuk, 'firstItem') ? $suratMasuk->firstItem() + $loop->index : $loop->iteration }}
              </td>
              <td class="px-6 py-3">{{ $row->perihal }}</td>
              <td class="px-6 py-3">{{ $row->asal_surat }}</td>
              <td class="px-6 py-3">{{ $row->nomor_surat }}</td>
              <td class="px-6 py-3">{{ \Carbon\Carbon::parse($row->tanggal_diterima)->format('d-m-Y') }}</td>
              <td class="px-6 py-3">{{ \Carbon\Carbon::parse($row->tanggal_surat)->format('d-m-Y') }}</td>
              <td class="px-6 py-3">
                <button onclick="showFileModal('{{ asset('storage/surat_masuk/' . $row->file_surat) }}')"
                  data-modal-target="fileModal" data-modal-toggle="fileModal"
                  class="px-2 py-1 font-medium text-white bg-blue-500 rounded hover:bg-blue-600">
                  <i class="fa-solid fa-eye"></i>
                </button>
              </td>
              <td class="px-6 py-3">
                @if ($row->status == 'Pending')
                  <span
                    class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">
                    {{ $row->status }}
                  </span>
                @else
                  <a href="{{ route('surat-masuk.telahan-staf', $row->id) }}" target="_blank">
                    <button class="px-2 py-1 font-medium text-white bg-green-500 rounded hover:bg-green-600">
                      <i class="fa-solid fa-file-pdf"></i>
                    </button>
                  </a>
                @endif
              </td>
              <td class="px-6 py-3 flex flex-wrap gap-2">
                <a href="{{ route('surat-masuk.edit', $row->id) }}">
                  <button class="px-2 py-1 font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                </a>
                <button
                  onclick="showDeleteModal('{{ route('surat-masuk.destroy', $row->id) }}', 'Yakin ingin menghapus ?')"
                  class="px-2 py-1 font-medium text-white bg-red-600 rounded hover:bg-red-700">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center px-6 py-3 text-gray-500">
                Tidak ada data dalam tabel.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>


  {{-- modal konfirmasi hapus  --}}
  <x-confirm-delete />

  {{-- modal file surat  --}}
  <div id="fileModal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-6xl max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            File Surat Masuk
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="fileModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <div class="p-4 md:p-5 space-y-4">
          <div id="fileViewer" class="w-full h-[500px]">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="ml-6 mt-4 text-sm font-medium">
    {{ $suratMasuk->links() }}
  </div>

  @push('scripts')
    <script>
      function showFileModal(fileUrl) {
        const modal = document.getElementById('fileModal');
        const viewer = document.getElementById('fileViewer');

        const extension = fileUrl.split('.').pop().toLowerCase();

        if (['pdf', 'jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
          viewer.innerHTML = `<iframe src="${fileUrl}" class="w-full h-full" frameborder="0"></iframe>`;
        } else {
          viewer.innerHTML =
            `<p class="text-gray-700">File tidak dapat ditampilkan, <a href="${fileUrl}" target="_blank" class="text-blue-600 underline">klik di sini untuk mengunduh</a>.</p>`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    </script>
  @endpush
@endsection
