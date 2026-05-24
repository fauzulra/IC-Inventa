<script>
    document.addEventListener("DOMContentLoaded", () => {
        // 1. Inisialisasi Elemen
        const searchInput = document.getElementById("searchInput");
        const showEntries = document.getElementById("showEntries");
        const tableBody = document.getElementById("tableBody");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const pageNumbers = document.getElementById("pageNumbers");

        // 2. Ambil semua baris data (tr) bawaan dari tabel
        const originalRows = Array.from(tableBody.querySelectorAll("tr"));
        let filteredRows = [...originalRows]; // Data yang sedang aktif (setelah di-search)
        let currentPage = 1;
        let rowsPerPage = parseInt(showEntries.value);

        // 3. Fungsi utama untuk menampilkan tabel
        function renderTable() {
            // Bersihkan tabel
            tableBody.innerHTML = "";

            // Hitung batasan data
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            // Render baris data
            if (paginatedRows.length === 0) {
                tableBody.innerHTML =
                    `<tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>`;
            } else {
                paginatedRows.forEach(row => tableBody.appendChild(row));
            }

            renderPagination();
        }

        // 4. Fungsi untuk mengatur tombol pagination
        function renderPagination() {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
            pageNumbers.innerHTML = "";

            // Atur state tombol Sebelum & Selanjutnya
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;

            // Generate angka halaman
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.textContent = i;

                // Style jika tombol adalah halaman aktif
                if (i === currentPage) {
                    btn.className =
                        "bg-[#854d3d] text-white w-9 h-9 flex items-center justify-center rounded-md font-bold";
                } else { // Style untuk halaman lainnya
                    btn.className =
                        "bg-white border border-gray-300 text-gray-700 w-9 h-9 flex items-center justify-center rounded-md font-bold hover:bg-gray-50 transition";
                }

                // Event saat angka diklik
                btn.addEventListener("click", () => {
                    currentPage = i;
                    renderTable();
                });

                pageNumbers.appendChild(btn);
            }
        }

        // 5. Event Listener untuk fitur Pencarian (Search)
        searchInput.addEventListener("input", (e) => {
            const term = e.target.value.toLowerCase();

            filteredRows = originalRows.filter(row => {
                // Mengambil semua teks dalam 1 baris, huruf kecilkan, lalu cek apakah mengandung kata kunci
                return row.textContent.toLowerCase().includes(term);
            });

            currentPage = 1; // Kembalikan ke halaman 1 setiap kali mencari
            renderTable();
        });

        // 6. Event Listener untuk fitur Tampilkan Entri (Show Entries)
        showEntries.addEventListener("change", (e) => {
            rowsPerPage = parseInt(e.target.value);
            currentPage = 1; // Kembalikan ke halaman 1
            renderTable();
        });

        // 7. Event Listener untuk tombol Sebelum
        prevBtn.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });

        // 8. Event Listener untuk tombol Selanjutnya
        nextBtn.addEventListener("click", () => {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });

        // Jalankan render pertama kali saat halaman dimuat
        renderTable();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
