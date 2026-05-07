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

        @media (max-width: 480px) {
            .wrap { padding: 12px; }
            .top { padding: 12px 14px; }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

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

    const state = { pdfDoc: null, pageNum: 1, pageCount: 0 };

    function canRenderPage(pageIndex1Based) {
        if (isPremiumActive) return true;
        return pageIndex1Based <= maxPage;
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

            window.alert('Daftarkan akun Premium anda untuk menikmati seluruh isi buku');

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
            await renderPage(1);
        } catch (err) {
            console.error(err);
            viewer.innerHTML = '<div style="color:#dc2626;font-weight:900;">Gagal memuat PDF.</div>';
        }
    })();
</script>
</body>
</html>

