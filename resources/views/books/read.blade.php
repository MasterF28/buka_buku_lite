<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - BUKA BUKU</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background:#f5f6fa; min-height:100vh; }
        .wrap { padding: 18px; max-width: 980px; margin: 0 auto; }
        .top { background: white; border-radius: 14px; padding: 14px 16px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .top h1 { font-size: 16px; color:#111827; margin-bottom: 6px; }
        .meta { color:#6b7280; font-size: 13px; display:flex; gap:10px; flex-wrap:wrap; }
        .badge { display:inline-flex; align-items:center; padding:6px 10px; border-radius:999px; font-weight:700; font-size:12px; }
        .badge.standar { background:#fff7ed; color:#9a3412; border:1px solid #fed7aa; }
        .badge.premium { background:#ecfdf5; color:#166534; border:1px solid #bbf7d0; }
        .actions { margin-top: 12px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:700; text-decoration:none; }
        .btn-primary { background:#e63946; color:white; }
        .btn-secondary { background:#e5e7eb; color:#111827; }
        .notice { margin-top: 12px; padding: 12px 14px; background: #fef2f2; border:1px solid #fecaca; border-radius: 12px; color: #991b1b; font-size: 13px; font-weight: 800; }

        .pdf-container { background:white; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        #pdfjs-toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom: 12px; }
        #page-info { color:#6b7280; font-size: 13px; }

        .page-wrap { position: relative; margin-bottom: 14px; }
        .page-canvas { width: 100%; height: auto; border-radius: 10px; overflow:hidden; border:1px solid #f3f4f6; }
        .page-blur { filter: blur(6px); opacity: .55; }
        .page-lock-overlay {
            position:absolute;
            inset: 0;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }
        .lock-pill { background: rgba(229,57,70,.95); color:white; padding: 10px 14px; border-radius: 999px; font-size: 13px; font-weight: 900; }
        .hint { background: rgba(255,255,255,.92); color:#111827; padding: 8px 12px; border-radius: 10px; font-size: 12px; font-weight: 800; }

        .quick-jump { margin-top: 14px; background: white; border-radius: 14px; padding: 12px 14px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .quick-jump-title { font-size: 12px; font-weight: 900; color:#111827; margin-bottom: 10px; letter-spacing: .2px; }
        .quick-jump-grid { display:flex; flex-wrap:wrap; gap:8px; }
        .qj-btn {
            width: 44px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #111827;
            font-weight: 900;
            cursor: pointer;
            transition: transform .05s ease;
        }
        .qj-btn:hover { transform: translateY(-1px); }
        .qj-btn.active {
            background: #e63946;
            border-color: #e63946;
            color: #fff;
        }

        @media (max-width: 480px) {
            .qj-btn { width: 40px; }
        }


        @media (max-width: 480px) {
            .wrap { padding: 12px; }
            .top { padding: 12px 14px; }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="wrap">
    <div class="top">
        <h1>{{ $book->title }}</h1>
        <div class="meta">
            <span>Oleh {{ $book->author }}</span>
            <span>•</span>
            <span>{{ $book->category->name ?? '-' }}</span>
            <span class="badge {{ $isPremiumActive ? 'premium' : 'standar' }}">{{ $isPremiumActive ? 'Premium Active' : 'Standar' }}</span>
        </div>

        @if(!$isPremiumActive)
            <div class="notice">Upgrade ke Membership Premium untuk membaca seluruh isi buku</div>
            <div class="actions">
                <a class="btn btn-primary" href="/books">Kembali Pilih Buku</a>
            </div>
        @endif
    </div>

    <div class="pdf-container">
        <div id="pdfjs-toolbar">
            <button id="prevBtn" class="btn btn-secondary" type="button">◀</button>
            <div id="page-info">Halaman <span id="pageNum">1</span> / <span id="pageCount">-</span></div>
            <button id="nextBtn" class="btn btn-secondary" type="button">▶</button>
        </div>

        <div class="quick-jump">
            <div class="quick-jump-title">Quick Jump</div>
            <div id="quickJumpContainer" class="quick-jump-grid" aria-label="Pilih halaman"></div>
        </div>

        <div id="pdfViewer"></div>
    </div>
</div>

        <script>

    const pdfUrl = @json($pdfUrl);
    // Pastikan URL mengarah ke public disk (pakai /storage prefix hasil Storage::url)

    const isPremiumActive = @json($isPremiumActive);
    const maxPage = @json($maxPage);

    // Worker
    const pdfjsLib = window.pdfjsLib;
    if (!pdfjsLib) {
        throw new Error('pdfjsLib is not defined. Check PDF.js CDN script load.');
    }
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const url = pdfUrl;
console.log('PDF URL:', url);
    const viewer = document.getElementById('pdfViewer');
    const pageNumEl = document.getElementById('pageNum');
    const pageCountEl = document.getElementById('pageCount');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const quickJumpContainer = document.getElementById('quickJumpContainer');

    const state = { pdfDoc: null, pageNum: 1, pageCount: 0 };


    function canRenderPage(pageIndex1Based) {
        if (isPremiumActive) return true;
        return pageIndex1Based <= maxPage;
    }

    function setActiveQuickJump(pageNumber) {
        if (!quickJumpContainer) return;
        quickJumpContainer.querySelectorAll('.qj-btn').forEach(btn => {
            btn.classList.toggle('active', Number(btn.dataset.page) === Number(pageNumber));
        });
    }

    function updateQuickJumpLockState() {
        if (!quickJumpContainer) return;
        quickJumpContainer.querySelectorAll('.qj-btn').forEach(btn => {
            const page = Number(btn.dataset.page);
            const locked = !canRenderPage(page);
            btn.disabled = locked;
            btn.style.opacity = locked ? '0.55' : '1';
            btn.style.cursor = locked ? 'not-allowed' : 'pointer';
            btn.title = locked ? 'Halaman terkunci (Premium)' : '';
        });
    }

    async function renderPage(pageNumber) {
        viewer.innerHTML = '';
        pageNumEl.textContent = pageNumber;

        const shouldLock = !canRenderPage(pageNumber);

        const wrap = document.createElement('div');
        wrap.className = 'page-wrap';

        const canvas = document.createElement('canvas');
        canvas.className = 'page-canvas';
        const ctx = canvas.getContext('2d');
        canvas.width = 1200;
        canvas.height = 1600;
        ctx.fillStyle = '#f3f4f6';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        if (shouldLock) {
            canvas.classList.add('page-blur');

            const overlay = document.createElement('div');
            overlay.className = 'page-lock-overlay';

            const pill = document.createElement('div');
            pill.className = 'lock-pill';
            pill.textContent = 'Terkunci';

            const hint = document.createElement('div');
            hint.className = 'hint';
            hint.textContent = 'Daftarkan akun Premium anda untuk menikmati seluruh isi buku';

            overlay.appendChild(pill);
            overlay.appendChild(hint);
            wrap.appendChild(canvas);
            wrap.appendChild(overlay);
            viewer.appendChild(wrap);

            Swal.fire({
                title: 'Akses Terbatas',
                text: 'Daftarkan akun Premium anda untuk menikmati seluruh isi buku',
                icon: 'info',
                confirmButtonText: 'OK',
                confirmButtonColor: '#e63946',
                allowOutsideClick: false
            });

            prevBtn.disabled = pageNumber <= 1;
            nextBtn.disabled = pageNumber >= state.pageCount;
            return;
        }

        const page = await state.pdfDoc.getPage(pageNumber);
        const viewport = page.getViewport({ scale: 1.5 });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        wrap.appendChild(canvas);
        viewer.appendChild(wrap);

        await page.render({ canvasContext: ctx, viewport }).promise;

        prevBtn.disabled = pageNumber <= 1;
        nextBtn.disabled = pageNumber >= state.pageCount;

        setActiveQuickJump(pageNumber);
        updateQuickJumpLockState();
    }


    prevBtn.addEventListener('click', async () => {
        if (state.pageNum <= 1) return;
        state.pageNum -= 1;
        await renderPage(state.pageNum);
    });

    nextBtn.addEventListener('click', async () => {
        if (state.pageNum >= state.pageCount) return;
        state.pageNum += 1;
        if (!isPremiumActive && state.pageNum > maxPage) {
            state.pageNum = Math.min(state.pageNum, state.pageCount);
        }
        await renderPage(state.pageNum);
    });

    // Quick Jump: chunk 15 halaman dengan navigasi << (mundur) dan >> (maju)
    function buildQuickJumpButtons(pageCount) {
        if (!quickJumpContainer) return;
        quickJumpContainer.innerHTML = '';

        const limit = isPremiumActive ? pageCount : Math.min(pageCount, maxPage);
        const safeLimit = Math.max(0, limit);

        // fallback: kalau PDF belum siap
        if (!safeLimit) return;

        // state untuk extend saat klik "..."
        // quickJumpExpandLevel: 0 (compact), 1 (extend berikutnya), 2, dst
        if (typeof state.quickJumpExpandLevel !== 'number') state.quickJumpExpandLevel = 0;

        const current = Number(state.pageNum) || 1;
        const expandLevel = state.quickJumpExpandLevel;

        // ===== Pagination 15 halaman =====
        // Button quick jump dibuat dalam bentuk chunk 15 halaman.

        const createQuickButton = (pageNumber) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'qj-btn';
            btn.textContent = pageNumber;
            btn.dataset.page = String(pageNumber);
            btn.addEventListener('click', async () => {
                if (!canRenderPage(pageNumber)) return;
                state.pageNum = pageNumber;
                setActiveQuickJump(pageNumber);
                await renderPage(pageNumber);
                viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            return btn;
        };

        // Render per 5 halaman + tombol >> untuk menampilkan 5 berikutnya
        // Ubah logic ringkas menjadi pagination chunk.
        const chunkSize = 5;

        // currentChunkStart disimpan di state
        if (typeof state.currentChunkStart !== 'number') {
            // mulai dari chunk yang mengandung halaman saat ini
            const cur = Number(state.pageNum) || 1;
            state.currentChunkStart = Math.max(1, cur - ((cur - 1) % chunkSize));
        } else {
            // kalau user sudah pindah halaman lewat tombol prev/next,
            // pastikan chunk ikut bergeser agar tombol aktif sesuai.
            const cur = Number(state.pageNum) || 1;
            const expectedChunkStart = Math.max(1, cur - ((cur - 1) % chunkSize));
            state.currentChunkStart = expectedChunkStart;
        }

        const chunkStart = Math.max(1, Math.min(safeLimit, state.currentChunkStart));
        const chunkEnd = Math.min(safeLimit, chunkStart + chunkSize - 1);

        // Reset list sebelum render
        quickJumpContainer.innerHTML = '';

        // tombol toolbar: << (chunk sebelumnya) - tampilkan di kiri paling kecil dari chunk
        const hasPrevChunk = chunkStart > 1;
        if (hasPrevChunk) {
            const prevBtnEl = document.createElement('button');
            prevBtnEl.type = 'button';
            prevBtnEl.className = 'qj-btn';
            prevBtnEl.textContent = '<<';
            prevBtnEl.style.width = '56px';
            prevBtnEl.style.fontSize = '12px';
            prevBtnEl.addEventListener('click', () => {
                state.currentChunkStart = Math.max(1, chunkStart - chunkSize);
                buildQuickJumpButtons(pageCount);
            });
            quickJumpContainer.appendChild(prevBtnEl);
        }

        // Buat tombol halaman dalam chunk
        for (let n = chunkStart; n <= chunkEnd; n++) {
            quickJumpContainer.appendChild(createQuickButton(n));
        }

        // tombol toolbar: >> (chunk berikutnya)
        const hasNextChunk = chunkEnd < safeLimit;
        if (hasNextChunk) {
            const nextBtnEl = document.createElement('button');
            nextBtnEl.type = 'button';
            nextBtnEl.className = 'qj-btn';
            nextBtnEl.textContent = '>>';
            nextBtnEl.style.width = '56px';
            nextBtnEl.style.fontSize = '12px';
            nextBtnEl.addEventListener('click', async () => {
                // naikkan chunk tanpa menunggu next page di viewer
                state.currentChunkStart = chunkEnd + 1;
                state.currentChunkStart = Math.max(1, state.currentChunkStart);

                const newChunkStart = state.currentChunkStart;
                const newChunkEnd = Math.min(safeLimit, newChunkStart + chunkSize - 1);

                // langsung lompat ke halaman pertama di chunk berikutnya
                // agar user tidak merasa "stay" di halaman lama
                const targetPage = newChunkStart;

                buildQuickJumpButtons(pageCount);
                await renderPage(targetPage);
                viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            quickJumpContainer.appendChild(nextBtnEl);
        }

        setActiveQuickJump(state.pageNum);
        // Pastikan tombol halaman di quick-jump tidak terkunci (berdasarkan membership)
        updateQuickJumpLockState();
    }




    (async function init() {
        try {
            const response = await fetch(url, { credentials: 'same-origin' });

            if (!response.ok) {
                throw new Error(`Gagal memuat PDF: ${response.status}`);
            }

            const arrayBuffer = await response.arrayBuffer();
            console.log('PDF bytes loaded:', arrayBuffer.byteLength);
            if (!arrayBuffer.byteLength) {
                throw new Error('PDF file returned zero bytes');
            }

            const uint8Array = new Uint8Array(arrayBuffer);
            const loadingTask = pdfjsLib.getDocument({ data: uint8Array });
            state.pdfDoc = await loadingTask.promise;
            state.pageCount = state.pdfDoc.numPages;
            pageCountEl.textContent = state.pageCount;

            buildQuickJumpButtons(state.pageCount);
            await renderPage(1);

        } catch (err) {
            console.error(err);
            viewer.innerHTML = '<div style="color:#dc2626;font-weight:900;">Gagal memuat PDF.</div>';
        }
    })();
</script>
</body>
</html>

