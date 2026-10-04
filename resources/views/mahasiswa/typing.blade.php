<x-app-layout>
    <x-slot name="header">
        Latihan Ketik 10 Jari (Blind System Typing)
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="space-y-1">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Uji Ketik 10 Jari (BST)</h2>
                <p class="text-xs sm:text-sm text-slate-500">Mulai ketik naskah di bawah untuk mengukur kecepatan (CPM) dan akurasi.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-sky-50 px-5 py-3 rounded-xl border border-sky-200 text-center">
                    <span class="block text-[10px] uppercase font-bold text-sky-600 tracking-wider">Target Kelulusan</span>
                    <span class="block text-2xl font-black text-sky-800">200+ CPM</span>
                    <span class="block text-[10px] text-slate-500 font-semibold">Akurasi Min. 95%</span>
                </div>
                <a href="{{ route('mahasiswa.typing.certificate') }}" target="_blank" class="px-5 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-sm transition-all flex items-center gap-2 border border-emerald-400/30 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    <span>Cetak Sertifikat BST</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Typing App Container -->
    <div x-data="typingApp()" x-init="initApp()" @keydown.window="handleGlobalKeydown($event)" class="space-y-6 mb-16 md:mb-0" data-aos="fade-up">
        <!-- Control Bar: Language, Time & Toggles (Kapital, Nomor, Tanda Baca) -->
        <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm space-y-4 text-xs sm:text-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Language Selector -->
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-500">Bahasa:</span>
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl">
                        <button @click="setLanguage('id')" :class="language === 'id' ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium'" class="px-3 py-1.5 rounded-lg transition-all active:scale-95">
                            Indonesia
                        </button>
                        <button @click="setLanguage('en')" :class="language === 'en' ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium'" class="px-3 py-1.5 rounded-lg transition-all active:scale-95">
                            English
                        </button>
                    </div>
                </div>

                <!-- Time Selector -->
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-500">Batas Waktu:</span>
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl">
                        <button @click="setTime(30)" :class="selectedTime === 30 ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium'" class="px-3 py-1.5 rounded-lg transition-all active:scale-95">
                            30s
                        </button>
                        <button @click="setTime(60)" :class="selectedTime === 60 ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium'" class="px-3 py-1.5 rounded-lg transition-all active:scale-95">
                            60s
                        </button>
                        <button @click="setTime(120)" :class="selectedTime === 120 ? 'bg-sky-600 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium'" class="px-3 py-1.5 rounded-lg transition-all active:scale-95">
                            120s
                        </button>
                    </div>
                </div>

                <!-- Reset Button -->
                <button @click="resetTest()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Acak Teks Baru</span>
                </button>
            </div>

            <hr class="border-slate-100">

            <!-- Checkbox Option Toggles (Kapital, Nomor, Tanda Baca) -->
            <div class="flex flex-wrap items-center gap-3 sm:gap-6 pt-1">
                <span class="font-bold text-slate-700 shrink-0">Variasi Teks Naskah:</span>

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" x-model="optCapital" @change="resetTest()" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4">
                    <span class="font-medium text-slate-700">Aa (Huruf Kapital)</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" x-model="optNumbers" @change="resetTest()" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4">
                    <span class="font-medium text-slate-700">123 (Nomor / Angka)</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" x-model="optPunctuation" @change="resetTest()" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4">
                    <span class="font-medium text-slate-700">!?,. (Tanda Baca)</span>
                </label>
            </div>
        </div>

        <!-- Live Score Board (Focusing on CPM) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
            <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm">
                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">CPM (Karakter/Menit)</span>
                <span class="block text-2xl sm:text-3xl font-black text-sky-600" x-text="cpm">0</span>
            </div>
            <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm">
                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">WPM (Kata/Menit)</span>
                <span class="block text-2xl sm:text-3xl font-black text-emerald-600" x-text="wpm">0</span>
            </div>
            <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm">
                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Akurasi (%)</span>
                <span class="block text-2xl sm:text-3xl font-black text-indigo-600" x-text="accuracy + '%'">100%</span>
            </div>
            <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200/80 shadow-sm">
                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Sisa Waktu</span>
                <span class="block text-2xl sm:text-3xl font-black text-slate-800" x-text="timeLeft + 's'">60s</span>
            </div>
        </div>

        <!-- Typing Test Main Box -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-md relative overflow-hidden" @click="focusInput()">
            <!-- Focus Instruction Banner -->
            <div x-show="!isFocused && !isFinished" class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-10 cursor-pointer" style="display: none;">
                <span class="bg-white text-slate-900 font-extrabold px-6 py-3 rounded-2xl shadow-xl border border-slate-200 text-xs sm:text-sm animate-bounce">
                    Klik di sini & mulai ketik (Timer akan berjalan otomatis)
                </span>
            </div>

            <!-- Status Indicator -->
            <div class="flex justify-between items-center mb-4 text-xs font-bold text-slate-400 pb-2 border-b border-slate-100">
                <span>Progres: <strong class="text-sky-600" x-text="(currentWordIndex + 1) + ' / ' + words.length"></strong> kata</span>
                <span x-show="isStarted && !isFinished" class="text-emerald-600 animate-pulse flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Memproses Ketikan...
                </span>
                <span x-show="!isStarted && !isFinished" class="text-amber-600">
                    Siap. Ketik huruf pertama untuk memulai.
                </span>
            </div>

            <!-- Text Display Area (2-Line Fixed Height for 1-Line Shift Scrolling) -->
            <div class="font-mono text-base sm:text-xl leading-relaxed tracking-wide text-slate-400 select-none h-[90px] sm:h-[110px] overflow-hidden flex flex-wrap gap-x-2.5 gap-y-2 p-3 bg-slate-50/50 rounded-xl border border-slate-100 scroll-smooth" id="wordsContainer">
                <template x-for="(word, wordIndex) in words" :key="wordIndex">
                    <span :id="'word-' + wordIndex" class="relative px-1 py-0.5 rounded transition-all" :class="{ 'bg-sky-100/80 ring-2 ring-sky-400 text-slate-900 font-bold': currentWordIndex === wordIndex }">
                        <template x-for="(char, charIndex) in word.split('')" :key="charIndex">
                            <span :class="getCharClass(wordIndex, charIndex)" x-text="char"></span>
                        </template>
                    </span>
                </template>
            </div>

            <!-- Hidden Actual Input -->
            <input 
                type="text" 
                x-ref="typingInput"
                @input="handleInput($event)"
                @keydown.space="handleSpace($event)"
                @focus="isFocused = true"
                @blur="isFocused = false"
                class="opacity-0 absolute top-0 left-0 w-full h-full cursor-default"
                :disabled="isFinished"
                autofocus
            />
        </div>

        <!-- Quick Restart Shortcut Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 p-3 bg-white rounded-xl border border-slate-200/80 shadow-2xs">
            <button @click="resetTest()" type="button" class="px-4 py-2 bg-slate-100 hover:bg-sky-50 text-slate-700 hover:text-sky-700 font-extrabold rounded-xl border border-slate-200 text-xs transition-all flex items-center gap-2 active:scale-95 shadow-2xs">
                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>🔄 Mulai Ulang / Reset</span>
            </button>
            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                <span>Shortcut Mulai Ulang:</span>
                <kbd class="px-2 py-0.5 bg-slate-100 text-slate-800 border border-slate-300 rounded font-mono font-bold text-[11px]">Tab</kbd>
                <span>atau</span>
                <kbd class="px-2 py-0.5 bg-slate-100 text-slate-800 border border-slate-300 rounded font-mono font-bold text-[11px]">Tab + Enter</kbd>
            </div>
        </div>

        <!-- Test Result Modal / Summary Card -->
        <div x-show="isFinished" style="display: none;" class="bg-gradient-to-br from-slate-900 via-slate-950 to-sky-950 text-white rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6" data-aos="zoom-in">
            <div class="flex justify-between items-center border-b border-white/10 pb-4">
                <div>
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Hasil Evaluasi Ketik 10 Jari</span>
                    <h3 class="text-2xl font-black">Tes Selesai</h3>
                </div>
                <button @click="resetTest()" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl shadow-lg transition-transform active:scale-95 text-xs sm:text-sm">
                    Uji Teks Lain
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-sky-600/30 backdrop-blur-md p-4 rounded-xl text-center border border-sky-400/30">
                    <span class="block text-xs text-sky-300 font-bold uppercase tracking-wider">Kecepatan Utama (CPM)</span>
                    <span class="block text-3xl sm:text-4xl font-black text-white" x-text="cpm">0</span>
                    <span class="block text-[10px] text-sky-200 mt-1 font-semibold">Karakter Per Menit</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-center border border-white/10">
                    <span class="block text-xs text-emerald-300 font-bold uppercase tracking-wider">WPM (Kata/Menit)</span>
                    <span class="block text-3xl sm:text-4xl font-black text-white" x-text="wpm">0</span>
                    <span class="block text-[10px] text-slate-300 mt-1 font-semibold">Words Per Minute</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-center border border-white/10">
                    <span class="block text-xs text-indigo-300 font-bold uppercase tracking-wider">Akurasi</span>
                    <span class="block text-3xl sm:text-4xl font-black text-white" x-text="accuracy + '%'">0%</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-center border border-white/10">
                    <span class="block text-xs text-rose-300 font-bold uppercase tracking-wider">Karakter Benar / Salah</span>
                    <span class="block text-2xl sm:text-3xl font-black text-white" x-text="correctChars + ' / ' + errors">0 / 0</span>
                </div>
            </div>

            <!-- Grade Feedback -->
            <div class="bg-white/5 p-4 sm:p-5 rounded-xl border border-white/10 text-xs sm:text-sm leading-relaxed">
                <template x-if="cpm >= 200 && accuracy >= 95">
                    <div class="text-emerald-400 font-bold space-y-1">
                        <p class="text-base">SANGAT MEMUASKAN (MEMENUHI TARGET BEC)</p>
                        <p class="text-xs text-slate-300 font-normal">Hasil ketikan Anda telah mencapai target standar kampus Bogor EduCARE (CPM ≥ 200 dan Akurasi ≥ 95%). Pertahankan ritme 10 jari Anda!</p>
                    </div>
                </template>

                <template x-if="cpm < 200 || accuracy < 95">
                    <div class="text-amber-300 font-bold space-y-1">
                        <p class="text-base">TERUS LATIHAN MANDIRI</p>
                        <p class="text-xs text-slate-300 font-normal">Target BEC adalah minimal 200 CPM dengan akurasi 95%. Terus latih penempatan jari manis & kelingking di rumah tanpa melihat papan ketik!</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Alpine.js Blind Typing Engine -->
    <script>
        function typingApp() {
            return {
                language: 'id',
                selectedTime: 60,
                timeLeft: 60,
                elapsedSeconds: 0,
                cpm: 0,
                wpm: 0,
                accuracy: 100,
                errors: 0,
                correctChars: 0,
                totalTypedChars: 0,
                words: [],
                currentWordIndex: 0,
                currentTypedWord: '',
                typedHistory: [],

                optCapital: true,
                optNumbers: true,
                optPunctuation: true,

                timer: null,
                isStarted: false,
                isFinished: false,
                isFocused: true,

                // Comprehensive Paragraph Passages for BEC Blind Typing Practice
                passagesID: [
                    "Surat dinas resmi nomor 045/BEC-AP/2026 diterbitkan pada tanggal 14 Agustus 2026 oleh Sekretaris Perusahaan. Dokumen ini berisi instruksi penataan kearsipan elektronik berbasis sistem MS Office 365. Seluruh mahasiswa jurusan Administrasi Perkantoran wajib menguasai korespondensi bisnis dalam 2 bahasa.",
                    "Dalam era e-commerce modern, pengelolaan bisnis digital membutuhkan analisis strategi pemasaran yang terukur. Pada kuartal 3 tahun 2026, penjualan produk UMKM Bogor meningkat sebesar 25.5 persen. Manajemen keuangan usaha harus dicatat dengan akurat dan rapi setiap akhir bulan.",
                    "Sistem kearsipan yang efektif mempermudah penemuan kembali berkas penting dalam waktu kurang dari 3 menit. Karakteristik utama seorang sekretaris profesional meliputi kedisiplinan, ketelitian, etika komunikasi verbal, serta kemampuan ketik 10 jari tanpa melihat keyboard.",
                    "Bogor EduCARE merupakan lembaga pendidikan vokasi yang melatih mahasiswa menjadi tenaga kerja siap pakai. Jam perkuliahan berlangsung dari pukul 07.00 WIB hingga 16.00 WIB setiap hari Senin sampai Jumat dengan standar kedisiplinan yang tinggi.",
                    "Pengelolaan administrasi sarana dan prasarana kantor memerlukan inventarisasi barang secara berkala. Kode barang AP-102 dan BM-204 telah diverifikasi oleh tim verifikator akademik pada tanggal 28 Juli 2026."
                ],

                passagesEN: [
                    "Official business letter reference number 102/BEC-BM/2026 was released on July 19, 2026. All students majoring in Business Management must master digital marketing strategies, spreadsheet tools, and professional English correspondence.",
                    "Efficient office administration reduces operational costs by up to 30 percent annually. A qualified executive secretary should type with speed over 200 CPM while maintaining an accuracy rate above 95 percent at all times.",
                    "Bogor EduCARE provides tuition-free vocational education for talented students. The daily campus schedule starts from 07:00 AM to 04:00 PM, focusing on English fluency, computer skills, and office management.",
                    "Effective communication in international trade requires strong vocabulary, formal writing skills, and fast keyboarding capabilities. Please send your weekly report to admin@bogoreducare.org before 05:00 PM."
                ],

                wordListID: [
                    "administrasi", "perkantoran", "sekretaris", "dokumen", "kearsipan", "korespondensi", "manajemen", "keuangan",
                    "pemasaran", "kewirausahaan", "analisis", "efisiensi", "komunikasi", "prosedur", "aplikasi", "teknologi", "komputer"
                ],

                wordListEN: [
                    "administration", "management", "correspondence", "secretary", "office", "document", "communication", "efficiency",
                    "productive", "service", "procedure", "application", "technology", "business", "marketing", "finance", "report"
                ],

                initApp() {
                    this.resetTest();
                },

                handleGlobalKeydown(e) {
                    if (e.key === 'Tab' || e.keyCode === 9) {
                        e.preventDefault();
                        this.resetTest();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        this.resetTest();
                    } else if (this.isFinished && (e.key === 'Enter' || e.key === ' ')) {
                        e.preventDefault();
                        this.resetTest();
                    }
                },

                setLanguage(lang) {
                    this.language = lang;
                    this.resetTest();
                },

                setTime(seconds) {
                    this.selectedTime = seconds;
                    this.resetTest();
                },

                generateText() {
                    let fullText = "";

                    if (this.optNumbers || this.optPunctuation || this.optCapital) {
                        // Select a random passage matching criteria
                        const pool = this.language === 'id' ? this.passagesID : this.passagesEN;
                        const randomPassage = pool[Math.floor(Math.random() * pool.length)];
                        fullText = randomPassage;

                        // Apply filters if disabled
                        if (!this.optCapital) {
                            fullText = fullText.toLowerCase();
                        }
                        if (!this.optNumbers) {
                            fullText = fullText.replace(/[0-9]/g, '');
                        }
                        if (!this.optPunctuation) {
                            fullText = fullText.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?"']/g, '');
                        }
                    } else {
                        // Clean simple words generator
                        const baseWords = this.language === 'id' ? this.wordListID : this.wordListEN;
                        const arr = [];
                        for (let i = 0; i < 50; i++) {
                            arr.push(baseWords[Math.floor(Math.random() * baseWords.length)]);
                        }
                        fullText = arr.join(' ');
                    }

                    // Split into array of words
                    this.words = fullText.trim().split(/\s+/);
                },

                resetTest() {
                    if (this.timer) clearInterval(this.timer);
                    this.generateText();
                    this.timeLeft = this.selectedTime;
                    this.elapsedSeconds = 0;
                    this.cpm = 0;
                    this.wpm = 0;
                    this.accuracy = 100;
                    this.errors = 0;
                    this.correctChars = 0;
                    this.totalTypedChars = 0;
                    this.currentWordIndex = 0;
                    this.currentTypedWord = '';
                    this.typedHistory = [];
                    this.isStarted = false;
                    this.isFinished = false;
                    
                    this.$nextTick(() => {
                        const container = document.getElementById('wordsContainer');
                        if (container) container.scrollTop = 0;
                        if (this.$refs.typingInput) {
                            this.$refs.typingInput.value = '';
                            this.focusInput();
                        }
                    });
                },

                focusInput() {
                    if (!this.isFinished && this.$refs.typingInput) {
                        this.$refs.typingInput.focus();
                    }
                },

                startTimer() {
                    this.isStarted = true;
                    this.timer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            this.elapsedSeconds++;
                            this.calculateStats();
                        } else {
                            this.finishTest();
                        }
                    }, 1000);
                },

                handleInput(e) {
                    if (this.isFinished) return;
                    if (!this.isStarted && e.target.value.length > 0) {
                        this.startTimer();
                    }

                    this.currentTypedWord = e.target.value;
                    this.calculateStats();
                    this.scrollToCurrentWord();
                },

                handleSpace(e) {
                    if (this.isFinished) return;
                    e.preventDefault();

                    const currentInput = this.currentTypedWord.trim();
                    const targetWord = this.words[this.currentWordIndex];

                    this.typedHistory[this.currentWordIndex] = currentInput;
                    
                    // Count correct chars
                    for (let i = 0; i < currentInput.length; i++) {
                        if (i < targetWord.length && currentInput[i] === targetWord[i]) {
                            this.correctChars++;
                        } else {
                            this.errors++;
                        }
                    }

                    if (currentInput === targetWord) {
                        this.correctChars += 1; // space
                    }

                    this.totalTypedChars += currentInput.length + 1;
                    this.currentWordIndex++;
                    this.currentTypedWord = '';
                    if (this.$refs.typingInput) {
                        this.$refs.typingInput.value = '';
                    }

                    // Auto finish if reached end of paragraph text
                    if (this.currentWordIndex >= this.words.length) {
                        this.finishTest();
                    } else {
                        this.calculateStats();
                        this.scrollToCurrentWord();
                    }
                },

                scrollToCurrentWord() {
                    this.$nextTick(() => {
                        const container = document.getElementById('wordsContainer');
                        const activeWord = document.getElementById('word-' + this.currentWordIndex);
                        const firstWord = document.getElementById('word-0');

                        if (container && activeWord && firstWord) {
                            // Calculate exact line top relative to first line
                            const lineTop = activeWord.offsetTop - firstWord.offsetTop;
                            if (container.scrollTop !== lineTop) {
                                container.scrollTo({
                                    top: lineTop,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    });
                },

                calculateStats() {
                    const minutes = Math.max(this.elapsedSeconds / 60, 0.01);
                    // CPM = correct chars / minutes
                    this.cpm = Math.round(this.correctChars / minutes) || 0;
                    // WPM = CPM / 5
                    this.wpm = Math.round(this.cpm / 5) || 0;

                    if (this.totalTypedChars > 0) {
                        this.accuracy = Math.max(0, Math.min(100, Math.round((this.correctChars / this.totalTypedChars) * 100)));
                    } else {
                        this.accuracy = 100;
                    }
                },

                finishTest() {
                    if (this.timer) clearInterval(this.timer);
                    this.isFinished = true;
                    this.calculateStats();
                    this.saveScoreToDb();
                },

                saveScoreToDb() {
                    if (this.cpm <= 0) return;
                    fetch('{{ route("mahasiswa.typing.save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cpm: this.cpm,
                            wpm: this.wpm,
                            accuracy: this.accuracy
                        })
                    }).then(res => res.json()).then(data => {
                        console.log('Skor BST berhasil disimpan ke Leaderboard:', data);
                    }).catch(err => console.error('Gagal menyimpan skor:', err));
                },

                getCharClass(wordIndex, charIndex) {
                    if (wordIndex < this.currentWordIndex) {
                        const typedWord = this.typedHistory[wordIndex] || '';
                        const targetChar = this.words[wordIndex][charIndex];
                        if (charIndex >= typedWord.length) {
                            return 'text-slate-300';
                        }
                        return typedWord[charIndex] === targetChar ? 'text-emerald-600 font-bold' : 'text-rose-600 bg-rose-100 underline font-bold px-0.5 rounded';
                    }

                    if (wordIndex === this.currentWordIndex) {
                        const targetChar = this.words[wordIndex][charIndex];
                        if (charIndex < this.currentTypedWord.length) {
                            return this.currentTypedWord[charIndex] === targetChar ? 'text-emerald-600 font-bold' : 'text-rose-600 bg-rose-100 underline font-bold px-0.5 rounded';
                        }
                    }

                    return 'text-slate-400';
                }
            }
        }
    </script>
</x-app-layout>
