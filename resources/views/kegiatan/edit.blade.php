@extends('layouts.app')

@section('content')

<div class="container px-2 py-8 mx-auto max-w-7xl fade-in">
    <!-- Main Form Column -->
    <div class="backup-card col-lg-8">
        <div class="kegiatan-card">
            <div class="kegiatan-card-header">
                <h5><i class="fas fa-edit me-2"></i>Form Edit Kegiatan</h5>
            </div>

            <div class="kegiatan-card-body">
                <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" id="formKegiatan">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar Section -->
                    <div class="form-section">
                        <h6 class="form-section-header">
                            <span class="form-section-icon info">
                                <i class="fas fa-info-circle"></i>
                            </span>
                            Informasi Dasar
                        </h6>

                        <div class="form-row-2">
                            <div>
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                        value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required
                                        placeholder="Contoh: Seminar Pengembang Diri 2026">
                                @error('nama_kegiatan')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="rencana" {{ old('status', $kegiatan->status) == 'rencana' ? 'selected' : '' }}>Rencana</option>
                                    <option value="berlangsung" {{ old('status', $kegiatan->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="selesai" {{ old('status', $kegiatan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ old('status', $kegiatan->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="arsip" {{ old('status', $kegiatan->status) == 'arsip' ? 'selected' : '' }}>Arsip</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required
                                        placeholder="Jelaskan tujuan, manfaat, dan rangkaian kegiatan dengan jelas...">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-lightbulb"></i>Tuliskan deskripsi yang lengkap dan informatif
                            </div>
                        </div>

                        <div class="form-row-2" style="margin-top: 20px;">
                            <div>
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="seminar" {{ old('kategori', $kegiatan->kategori) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="pelatihan" {{ old('kategori', $kegiatan->kategori) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                    <option value="lomba" {{ old('kategori', $kegiatan->kategori) == 'lomba' ? 'selected' : '' }}>Lomba</option>
                                    <option value="rapat" {{ old('kategori', $kegiatan->kategori) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                    <option value="sosial" {{ old('kategori', $kegiatan->kategori) == 'sosial' ? 'selected' : '' }}>Kegiatan Sosial</option>
                                    <option value="internal" {{ old('kategori', $kegiatan->kategori) == 'internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="eksternal" {{ old('kategori', $kegiatan->kategori) == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                    <option value="lainnya" {{ old('kategori', $kegiatan->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis" id="jenisKegiatan" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="offline" {{ old('jenis', $kegiatan->jenis) == 'offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                                    <option value="online" {{ old('jenis', $kegiatan->jenis) == 'online' ? 'selected' : '' }}>Online (Daring)</option>
                                    <option value="hybrid" {{ old('jenis', $kegiatan->jenis) == 'hybrid' ? 'selected' : '' }}>Hybrid (Campuran)</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Waktu & Tempat Section -->
                    <div class="form-section">
                        <h6 class="form-section-header">
                            <span class="form-section-icon time">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            Waktu & Tempat
                        </h6>

                        <div class="form-row-2">
                            <div>
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai->format('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                        value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai->format('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tempat -->
                        <div id="tempatGroup" style="margin-top: 20px;">
                            <label class="form-label">Tempat/Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror"
                                    value="{{ old('tempat', $kegiatan->tempat) }}" placeholder="Contoh: Aula Utama Kampus, Ruang Seminar, dll.">
                            @error('tempat')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Link Meeting -->
                        <div id="linkMeetingGroup" style="margin-top: 20px; display: none;">
                            <label class="form-label">Link Meeting</label>
                            <input type="url" name="link_meeting" class="form-control @error('link_meeting') is-invalid @enderror"
                                    value="{{ old('link_meeting', $kegiatan->link_meeting) }}" placeholder="https://zoom.us/j/xxx atau https://meet.google.com/xxx">
                            @error('link_meeting')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-video"></i>Link untuk pertemuan online (Zoom, Google Meet, dll.)
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto Section -->
                    <div class="form-section">
                        <h6 class="form-section-header">
                            <span class="form-section-icon photo">
                                <i class="fas fa-camera"></i>
                            </span>
                            Foto Utama Kegiatan
                        </h6>

                        <div class="upload-area" id="uploadArea">
                            <div id="uploadPlaceholder" class="upload-placeholder" style="{{ $kegiatan->foto_utama ? 'display:none;' : '' }}">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="fw-500">Drag & drop foto utama kegiatan di sini</p>
                                <p class="text-muted">atau</p>
                                <button type="button" class="btn-upload" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-folder-open me-1"></i>Pilih File
                                </button>
                                <p class="text-muted" style="font-size: 0.85rem; margin-top: 15px;">
                                    <i class="fas fa-info-circle"></i>Maksimal 5MB. Format: JPG, PNG, GIF, WebP
                                </p>
                            </div>
                            <div id="uploadPreview" style="{{ $kegiatan->foto_utama ? 'display:block;' : 'display:none;' }}" class="upload-preview">
                                <img id="previewImage" src="{{ $kegiatan->foto_utama ? Storage::url($kegiatan->foto_utama) : '' }}" alt="Preview">
                                <div class="gap-2 d-flex justify-content-center" style="gap: 10px;">
                                    <button type="button" class="btn-upload" style="background-color: #ff9800;" onclick="replaceImage()">
                                        <i class="fas fa-sync me-1"></i>Ganti
                                    </button>
                                    <button type="button" class="btn-upload" style="background-color: #dc3545;" onclick="removeImage()">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                            <input type="file" name="foto_utama" id="fileInput" class="d-none" accept="image/*">
                            @error('foto_utama')
                                <div class="text-danger" style="font-size: 0.85rem; margin-top: 10px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Pengaturan Section -->
                    <div class="form-section">
                        <h6 class="form-section-header">
                            <span class="form-section-icon settings">
                                <i class="fas fa-cog"></i>
                            </span>
                            Pengaturan
                        </h6>

                        <div class="form-row-2">
                            <div>
                                <label class="form-label">Kuota Peserta</label>
                                <input type="number" name="kuota" class="form-control @error('kuota') is-invalid @enderror"
                                        value="{{ old('kuota', $kegiatan->kuota) }}" min="0">
                                @error('kuota')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-lightbulb"></i>Isi 0 untuk kuota tidak terbatas
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Dokumen Proposal (opsional)</label>
                                <input type="file" name="dokumen_proposal" class="form-control @error('dokumen_proposal') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx">
                                @error('dokumen_proposal')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-file"></i>Format: PDF, DOC, DOCX. Maksimal 10MB.
                                    @if($kegiatan->dokumen_proposal)
                                        <br><a href="{{ Storage::url($kegiatan->dokumen_proposal) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-download me-1"></i>Download file saat ini
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="btn-form btn-form-cancel">
                            <i class="fas fa-times me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn-form btn-form-submit">
                            <i class="fas fa-save me-2"></i> Update Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Panduan -->
    <div class="col-lg-4 kegiatan-sidebar">
        <!-- Tips Box -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <h6><i class="fas fa-lightbulb me-2"></i>Tips & Panduan</h6>
            </div>
            <div class="sidebar-card-body">
                <div class="tips-box info">
                    <h6><i class="fas fa-check-circle me-2"></i>Langkah Edit Kegiatan:</h6>
                    <ol>
                        <li>Update informasi yang diperlukan</li>
                        <li>Pastikan tanggal dan waktu sudah akurat</li>
                        <li>Ganti foto jika diperlukan</li>
                        <li>Update dokumen proposal jika ada perubahan</li>
                        <li>Simpan perubahan</li>
                    </ol>
                </div>

                <hr class="divider">

                <h6 style="margin-bottom: 15px; color: var(--text-dark); font-weight: 600;">
                    <i class="fas fa-tags me-2"></i>Status Kegiatan:
                </h6>
                <div style="margin-bottom: 20px;">
                    <div class="status-item">
                        <span class="status-badge rencana">Rencana</span>
                        <p class="status-description">Kegiatan masih dalam tahap perencanaan</p>
                    </div>
                    <div class="status-item">
                        <span class="status-badge berlangsung">Berlangsung</span>
                        <p class="status-description">Kegiatan sedang berjalan saat ini</p>
                    </div>
                    <div class="status-item">
                        <span class="status-badge selesai">Selesai</span>
                        <p class="status-description">Kegiatan telah selesai dilaksanakan</p>
                    </div>
                    <div class="status-item">
                        <span class="status-badge dibatalkan">Dibatalkan</span>
                        <p class="status-description">Kegiatan telah dibatalkan</p>
                    </div>
                    <div class="status-item">
                        <span class="status-badge arsip">Arsip</span>
                        <p class="status-description">Kegiatan di-arsipkan</p>
                    </div>
                </div>

                <hr class="divider">

                <div class="tips-box warning">
                    <h6><i class="fas fa-star me-2"></i>Tips Foto</h6>
                    <ul>
                        <li>Gunakan foto berkualitas tinggi</li>
                        <li>Hindari foto yang blur atau gelap</li>
                        <li>Ukuran optimal: 1200x800px atau lebih besar</li>
                        <li>Pastikan foto relevan dengan kegiatan</li>
                    </ul>
                </div>

                <hr class="divider">

                <div class="tips-box alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Kegiatan</h6>
                    <ul>
                        <li><strong>Dibuat:</strong> {{ $kegiatan->created_at->format('d M Y H:i') }}</li>
                        <li><strong>Terakhir Update:</strong> {{ $kegiatan->updated_at->format('d M Y H:i') }}</li>
                        <li><strong>Total Peserta:</strong> {{ $kegiatan->peserta->count() }}</li>
                        <li><strong>Peserta Diterima:</strong> {{ $kegiatan->peserta->where('status_pendaftaran', 'diterima')->count() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animasi untuk kartu */
    .backup-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .backup-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
        /* Efek shimmer pada hover */
    .backup-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.7s;
    }

    .backup-card:hover::before {
        left: 100%;
    }

    /* Efek fade in untuk konten */
    .fade-in {
        animation: fadeIn 0.6s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>

@push('scripts')
<script>
// Toggle form based on activity type
document.getElementById('jenisKegiatan').addEventListener('change', function() {
    const tempatGroup = document.getElementById('tempatGroup');
    const linkGroup = document.getElementById('linkMeetingGroup');
    const tempatInput = tempatGroup.querySelector('input[name="tempat"]');
    const linkInput = linkGroup.querySelector('input[name="link_meeting"]');

    if (this.value === 'online') {
        tempatGroup.style.display = 'none';
        linkGroup.style.display = 'block';
        tempatInput.removeAttribute('required');
        linkInput.setAttribute('required', 'required');
    } else if (this.value === 'offline') {
        tempatGroup.style.display = 'block';
        linkGroup.style.display = 'none';
        tempatInput.setAttribute('required', 'required');
        linkInput.removeAttribute('required');
    } else if (this.value === 'hybrid') {
        tempatGroup.style.display = 'block';
        linkGroup.style.display = 'block';
        tempatInput.setAttribute('required', 'required');
        linkInput.setAttribute('required', 'required');
    } else {
        tempatGroup.style.display = 'none';
        linkGroup.style.display = 'none';
        tempatInput.removeAttribute('required');
        linkInput.removeAttribute('required');
    }
});

// File upload handling
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5MB');
        event.target.value = '';
        return;
    }

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        alert('Format file harus JPG, PNG, GIF, atau WebP');
        event.target.value = '';
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('uploadPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function removeImage() {
    document.getElementById('fileInput').value = '';
    document.getElementById('uploadPreview').style.display = 'none';
    document.getElementById('uploadPlaceholder').style.display = 'block';
}

function replaceImage() {
    document.getElementById('fileInput').click();
}

// Initialize file input
document.getElementById('fileInput').addEventListener('change', handleFileSelect);

// Drag and drop
const uploadArea = document.getElementById('uploadArea');
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');

    const fileInput = document.getElementById('fileInput');
    fileInput.files = e.dataTransfer.files;

    const event = new Event('change');
    fileInput.dispatchEvent(event);
});

// Set initial state
window.addEventListener('DOMContentLoaded', function() {
    const jenisSelect = document.getElementById('jenisKegiatan');
    if (jenisSelect.value) {
        jenisSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@endsection
