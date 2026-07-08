@foreach($books as $book)
<div class="flex justify-between items-center p-4 border-b">
    <div>
        <h3 class="text-lg font-bold">{{ $book->judul }}</h3>
        <span class="text-sm text-green-600">Tersedia</span>
    </div>
    
    <button type="button" 
            class="bg-blue-500 text-white px-4 py-2 rounded-xl" 
            data-bs-toggle="modal" 
            data-bs-target="#modalPinjam{{ $book->id }}">
        Pinjam Buku
    </button>
</div>

<div class="modal fade" id="modalPinjam{{ $book->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold">Konfirmasi Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('pinjam.buku', $book->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin meminjam buku <strong>{{ $book->judul }}</strong>?</p>
                    
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700">Durasi Peminjaman (Hari)</label>
                        <select name="durasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="3">3 Hari</option>
                            <option value="7">7 Hari</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ya, Pinjam</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach