<x-app-layout>
    {{-- Push ke <head>: FA + Summernote CSS harus sebelum override Plus Jakarta Sans --}}
    @push('head-styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush

    {{-- Push ke sebelum </body>: jQuery + Summernote JS + init --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
          $(document).ready(function() {
              $('#konten').summernote({
                  placeholder: '✍️  Ketik atau tempel materi pelajaran di sini...',
                  tabsize: 4,
                  height: 560,
                  minHeight: 460,
                  focus: false,
                  dialogsInBody: true,
                  dialogsFade: true,

                  fontNames: [
                      'Plus Jakarta Sans', 'Arial', 'Arial Black', 'Comic Sans MS',
                      'Courier New', 'Georgia', 'Impact',
                      'Times New Roman', 'Trebuchet MS', 'Verdana'
                  ],
                  fontNamesIgnoreCheck: ['Plus Jakarta Sans'],
                  fontSizes: ['8','9','10','11','12','13','14','15','16','18','20','22','24','28','32','36','42','48','64'],

                  styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'blockquote', 'pre'],

                  toolbar: [
                      ['history',  ['undo', 'redo']],
                      ['style',    ['style']],
                      ['fontsize', ['fontsize']],
                      ['font',     ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                      ['script',   ['superscript', 'subscript']],
                      ['fontname', ['fontname']],
                      ['color',    ['color']],
                      ['para',     ['ul', 'ol', 'paragraph']],
                      ['height',   ['height']],
                      ['table',    ['table']],
                      ['insert',   ['link', 'picture', 'video', 'hr']],
                      ['view',     ['fullscreen', 'codeview', 'help']],
                  ],
              });
          });
        </script>
    @endpush

    <style>
        /* ============================================================
           WORD-LIKE SUMMERNOTE EDITOR — MIFDA
        ============================================================ */

        /* Outer frame */
        .note-editor.note-frame {
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
        }

        /* Toolbar — light ribbon like Microsoft Word */
        .note-editor .note-toolbar {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 10px;
            flex-wrap: wrap;
            gap: 1px;
        }

        /* Group separators */
        .note-toolbar .note-btn-group {
            border-right: 1px solid #e2e8f0;
            margin-right: 3px;
            padding-right: 3px;
        }
        .note-toolbar .note-btn-group:last-child { border-right: none; }

        /* Gray document canvas — like Word's page area */
        .note-editor .note-editing-area {
            background: #dde1e7;
            padding: 24px 20px;
        }

        /* White A4-like paper page */
        .note-editor .note-editable {
            background: #ffffff !important;
            max-width: 860px;
            margin: 0 auto;
            min-height: 480px;
            padding: 52px 72px !important;
            box-shadow: 0 2px 18px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.04);
            font-size: 14px !important;
            line-height: 1.9 !important;
            color: #1e293b !important;
            border-radius: 2px;
        }

        /* Mobile: tighter padding */
        @media (max-width: 640px) {
            .note-editor .note-editing-area { padding: 10px 8px; }
            .note-editor .note-editable { padding: 24px 20px !important; }
        }

        /* Toolbar buttons */
        .note-editor .note-toolbar .note-btn {
            padding: 3px 7px;
            border-radius: 4px;
            font-size: 12px;
            color: #475569;
            border: 1px solid transparent;
            transition: all .12s;
        }
        .note-editor .note-toolbar .note-btn:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }
        .note-editor .note-toolbar .note-btn.active {
            background-color: #dbeafe;
            color: #3730a3;
            border-color: #bfdbfe;
        }

        /* Dropdown menus */
        .note-dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.13);
            border: 1px solid #e2e8f0;
        }
        .note-dropdown-item:hover { background: #eef2ff !important; color: #4f46e5; }

        /* Status bar (word count) */
        .note-status-bar {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 4px 12px;
            font-size: 11px;
            color: #94a3b8;
        }

        /* Fullscreen — paper fills window */
        .note-editor.fullscreen .note-editing-area { padding: 30px !important; }
        .note-editor.fullscreen .note-editable { max-width: none !important; margin: 0 !important; padding: 48px 80px !important; }

        /* Font icon tidak perlu di-override lagi —
           global font sudah tidak pakai !important sejak fix di app.blade.php */
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ $mode === 'ujian' ? route('guru.ujian.index') : route('guru.dashboard') }}"
               class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-slate-600 text-xs md:text-sm flex items-center gap-2 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
                <span class="hidden sm:inline">Kembali</span>
            </a>

            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-2xl border border-white/20">{{ $mode === 'ujian' ? '📋' : '➕' }}</span>
                {{ $mode === 'ujian' ? 'Buat Ujian Semester' : 'Tambah Misi Belajar' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="h-3 md:h-4 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-60 z-0 pointer-events-none"></div>

                <div class="p-6 md:p-10 lg:p-12 relative z-10">
                    
                    <div class="mb-10 md:mb-12 text-center">
                        @if($mode === 'ujian')
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600 rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center text-4xl md:text-5xl mx-auto mb-5 shadow-sm border border-blue-100 rotate-6 hover:rotate-0 transition-transform duration-300">
                            📋
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Buat Ujian Semester</h3>
                        <p class="text-xs md:text-sm font-bold text-slate-400 mt-2 max-w-lg mx-auto">Isi materi soal ujian, pilih jenis ujian ganjil/genap, lalu atur jadwal buka dan tutup ujian.</p>
                        @else
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-emerald-50 to-teal-100 text-emerald-600 rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center text-4xl md:text-5xl mx-auto mb-5 shadow-sm border border-emerald-100 rotate-6 hover:rotate-0 transition-transform duration-300">
                            📚
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Detail Misi Belajar</h3>
                        <p class="text-xs md:text-sm font-bold text-slate-400 mt-2 max-w-lg mx-auto">Sajikan materi pelajaran semenarik mungkin menggunakan teks, dokumen, atau video untuk siswa-siswamu.</p>
                        @endif
                    </div>

                    <form action="{{ route('guru.materi.store') }}" method="POST" class="space-y-6 md:space-y-8" enctype="multipart/form-data" x-data="{ tipeMateri: 'teks' }">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-2">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="mapel_id" required class="w-full border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-700 px-4 md:px-5 py-3.5 md:py-4 transition-all shadow-sm appearance-none cursor-pointer">
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($mapels as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_mapel }} ({{ $m->kelas->nama_kelas }})</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('mapel_id') <p class="text-rose-500 text-[10px] md:text-xs mt-2 font-bold px-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-2">Judul Misi <span class="text-rose-500">*</span></label>
                                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-800 px-4 md:px-5 py-3.5 md:py-4 transition-all shadow-sm placeholder-slate-400" placeholder="Contoh: Mengenal Angka 1-10" required>
                                @error('judul') <p class="text-rose-500 text-[10px] md:text-xs mt-2 font-bold px-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="p-6 md:p-8 bg-slate-50/80 rounded-[1.5rem] md:rounded-[2rem] border border-slate-100 space-y-6 shadow-inner relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-2 h-full" style="background-color: #10b981;"></div>

                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-2 px-2 flex items-center gap-2">
                                    <span class="text-base">⚙️</span> Pilih Format Materi Pokok <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="tipe" x-model="tipeMateri" class="w-full border border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-black px-4 md:px-5 py-3.5 md:py-4 text-slate-700 bg-white cursor-pointer transition-all appearance-none" required>
                                        <option value="teks">📝 Artikel / Teks Saja</option>
                                        <option value="youtube">🎬 Tautkan Video YouTube</option>
                                        <option value="video_lokal">🎞️ Upload Video Lokal (MP4)</option>
                                        <option value="dokumen">📄 Upload File (PDF, Word, Excel, PPT)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-200 min-h-[120px] flex flex-col justify-center transition-all">
                                
                                <div x-show="tipeMateri === 'teks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="text-center text-slate-400 font-bold text-xs md:text-sm">
                                    <span class="text-4xl block mb-3 grayscale opacity-50">📝</span>
                                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-[10px] uppercase tracking-widest mb-2 inline-block">Format Terpilih</span><br>
                                    Anda memilih format Artikel. Silakan ketikkan materi selengkapnya pada kotak <span class="text-slate-600">Penjelasan / Isi Materi</span> di bawah.
                                </div>

                                <div x-show="tipeMateri === 'youtube'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                                    <label class="block text-[10px] md:text-xs font-black text-rose-500 uppercase tracking-widest mb-2">Tautan Video YouTube</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-xl">🎬</span>
                                        <input type="url" name="youtube_link" class="w-full border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-transparent font-bold text-slate-700 px-4 py-3 md:py-4 pl-12 transition-all" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxx">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold">*Masukkan link lengkap (URL) dari video YouTube yang ingin disematkan.</p>
                                </div>

                                <div x-show="tipeMateri === 'video_lokal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                                    <label class="block text-[10px] md:text-xs font-black text-indigo-500 uppercase tracking-widest mb-2">Upload File Video</label>
                                    <div class="relative bg-slate-50 border border-slate-200 rounded-2xl shadow-sm hover:border-indigo-300 transition-colors">
                                        <input type="file" name="file_video" accept="video/mp4,video/x-m4v,video/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] md:file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold">*Format yang didukung: MP4. Maksimal ukuran file bergantung pada pengaturan server.</p>
                                </div>

                                <div x-show="tipeMateri === 'dokumen'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                                    <label class="block text-[10px] md:text-xs font-black text-orange-500 uppercase tracking-widest mb-2">Upload Dokumen/Berkas</label>
                                    <div class="relative bg-slate-50 border border-slate-200 rounded-2xl shadow-sm hover:border-orange-300 transition-colors">
                                        <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] md:file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all cursor-pointer">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold">*Format didukung: PDF, Word (.doc/.docx), Excel, PowerPoint.</p>
                                </div>

                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-2 flex items-center gap-2">
                                Penjelasan / Isi Materi <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="konten" id="konten" class="w-full border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-medium text-slate-700 px-5 py-4 transition-all shadow-sm resize-y" rows="8" placeholder="Ketikkan teks materi secara lengkap...">{{ old('konten') }}</textarea>
                            @error('konten') <p class="text-rose-500 text-[10px] md:text-xs mt-2 font-bold px-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="w-full md:w-1/2">
                            <label class="block text-[10px] md:text-xs font-black text-amber-600 uppercase tracking-widest mb-2 px-2">Hadiah XP (Poin Penyelesaian)</label>
                            <div class="relative shadow-sm rounded-2xl border border-amber-200 bg-amber-50 focus-within:ring-2 focus-within:ring-amber-400 transition-all">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-amber-500 text-xl md:text-2xl font-black drop-shadow-sm">⚡</span>
                                <input type="number" name="xp_reward" value="{{ old('xp_reward', 10) }}" class="w-full border-transparent bg-transparent focus:ring-0 font-black text-amber-700 text-lg md:text-xl px-4 py-3.5 md:py-4 pl-14" required min="0">
                            </div>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-2 px-2 font-bold">*Tentukan jumlah Poin XP yang akan didapatkan siswa saat mengklik "Selesai" pada materi ini.</p>
                        </div>

                        {{-- [FIX] Jenis Materi: Latihan atau Ujian Semester --}}
                        <div x-data="{ isUjian: {{ $mode === 'ujian' ? 'true' : 'false' }} }" class="w-full">
                            <label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-3 px-2">Jenis Materi / Soal</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="latihan" class="peer sr-only"
                                           {{ $mode !== 'ujian' ? 'checked' : '' }} x-on:change="isUjian = false">
                                    <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white text-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50">
                                        <div class="text-2xl mb-1">📚</div>
                                        <div class="font-black text-sm text-slate-700">Latihan Biasa</div>
                                        <div class="text-[10px] text-slate-400 mt-1">Soal untuk latihan, bisa dikerjakan kapan saja</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="ujian_ganjil" class="peer sr-only"
                                           {{ $mode === 'ujian' ? 'checked' : '' }} x-on:change="isUjian = true">
                                    <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-2xl mb-1">📋</div>
                                        <div class="font-black text-sm text-slate-700">Ujian Semester Ganjil</div>
                                        <div class="text-[10px] text-slate-400 mt-1">Dibuka sesuai jadwal yang ditetapkan</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="ujian_genap" class="peer sr-only"
                                           x-on:change="isUjian = true">
                                    <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white text-center transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50">
                                        <div class="text-2xl mb-1">📋</div>
                                        <div class="font-black text-sm text-slate-700">Ujian Semester Genap</div>
                                        <div class="text-[10px] text-slate-400 mt-1">Dibuka sesuai jadwal yang ditetapkan</div>
                                    </div>
                                </label>
                            </div>

                            <div x-show="isUjian" x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-4">
                                <div>
                                    <label class="block font-black text-slate-700 mb-2 text-xs uppercase tracking-widest">⏰ Jadwal Mulai Ujian</label>
                                    <input type="datetime-local" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}"
                                           class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 font-medium text-sm focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                                    <p class="text-[10px] text-slate-400 mt-1">Siswa tidak bisa buka ujian sebelum waktu ini</p>
                                </div>
                                <div>
                                    <label class="block font-black text-slate-700 mb-2 text-xs uppercase tracking-widest">⏰ Jadwal Selesai Ujian</label>
                                    <input type="datetime-local" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}"
                                           class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 font-medium text-sm focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                                    <p class="text-[10px] text-slate-400 mt-1">Setelah waktu ini ujian otomatis ditutup</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 md:gap-4 pt-8 md:pt-10 border-t border-slate-100">
                            <a href="{{ $mode === 'ujian' ? route('guru.ujian.index') : route('guru.dashboard') }}"
                               class="w-full sm:w-auto px-6 md:px-8 py-3.5 md:py-4 bg-slate-100 text-slate-500 rounded-xl md:rounded-2xl font-black hover:bg-slate-200 transition-all uppercase text-[10px] md:text-xs tracking-widest flex items-center justify-center">
                                Batal
                            </a>

                            @if($mode === 'ujian')
                            <button type="submit" class="w-full sm:w-auto px-8 md:px-10 py-3.5 md:py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl md:rounded-2xl font-black shadow-lg shadow-blue-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all uppercase text-[10px] md:text-xs tracking-widest flex items-center justify-center gap-2 border border-blue-400/50">
                                <span>📋</span> Simpan Ujian
                            </button>
                            @else
                            <button type="submit" class="w-full sm:w-auto px-8 md:px-10 py-3.5 md:py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl md:rounded-2xl font-black shadow-lg shadow-emerald-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all uppercase text-[10px] md:text-xs tracking-widest flex items-center justify-center gap-2 border border-emerald-400/50">
                                <span>🚀</span> Simpan Misi Belajar
                            </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>