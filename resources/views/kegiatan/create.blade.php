@extends('layouts.app')

@section('content')
<div class="container px-2 py-8 mx-auto max-w-7xl fade-in">
    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="kegiatan-card">
                <div class="kegiatan-card-header">
                    <h5><i class="fas fa-edit me-2"></i>Form Kegiatan Baru</h5>
                    <div class="kegiatan-card-header-icon">
                    </div>
                </div>

                <div class="kegiatan-card-body">
                    <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data" id="formKegiatan">
                        @csrf

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
                                           value="{{ old('nama_kegiatan') }}" required
                                           placeholder="Contoh: Seminar Pengembang Diri 2026">
                                    @error('nama_kegiatan')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="rencana" {{ old('status') == 'rencana' ? 'selected' : '' }}>Rencana</option>
                                        <option value="berlangsung" {{ old('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required
                                          placeholder="Jelaskan tujuan, manfaat, dan rangkaian kegiatan dengan jelas...">{{ old('deskripsi') }}</textarea>
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
                                        <option value="seminar" {{ old('kategori') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                        <option value="pelatihan" {{ old('kategori') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                        <option value="lomba" {{ old('kategori') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                                        <option value="rapat" {{ old('kategori') == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                        <option value="sosial" {{ old('kategori') == 'sosial' ? 'selected' : '' }}>Kegiatan Sosial</option>
                                        <option value="internal" {{ old('kategori') == 'internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="eksternal" {{ old('kategori') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                        <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select name="jenis" id="jenisKegiatan" class="form-select @error('jenis') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="offline" {{ old('jenis') == 'offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                                        <option value="online" {{ old('jenis') == 'online' ? 'selected' : '' }}>Online (Daring)</option>
                                        <option value="hybrid" {{ old('jenis') == 'hybrid' ? 'selected' : '' }}>Hybrid (Campuran)</option>
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
                                           value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai') }}" required>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tempat -->
                            <div id="tempatGroup" style="margin-top: 20px;">
                                <label class="form-label">Tempat/Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror"
                                       value="{{ old('tempat') }}" placeholder="Contoh: Aula Utama Kampus, Ruang Seminar, dll.">
                                @error('tempat')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link Meeting -->
                            <div id="linkMeetingGroup" style="margin-top: 20px; display: none;">
                                <label class="form-label">Link Meeting</label>
                                <input type="url" name="link_meeting" class="form-control @error('link_meeting') is-invalid @enderror"
                                       value="{{ old('link_meeting') }}" placeholder="https://zoom.us/j/xxx atau https://meet.google.com/xxx">
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
                                <div id="uploadPlaceholder" class="upload-placeholder">
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
                                <div id="uploadPreview" style="display: none;" class="upload-preview">
                                    <img id="previewImage" src="" alt="Preview">
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
                                           value="{{ old('kuota', 0) }}" min="0">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <a href="{{ route('kegiatan.index') }}" class="btn-form btn-form-cancel">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-form btn-form-submit">
                                <i class="fas fa-save me-2"></i> Simpan Kegiatan
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
                        <h6><i class="fas fa-check-circle me-2"></i>Langkah Membuat Kegiatan:</h6>
                        <ol>
                            <li>Isi nama kegiatan yang jelas dan menarik</li>
                            <li>Tuliskan deskripsi yang lengkap dan informatif</li>
                            <li>Pastikan tanggal dan waktu sudah benar</li>
                            <li>Upload foto utama yang relevan dan berkualitas</li>
                            <li>Tentukan kuota peserta jika diperlukan</li>
                            <li>Simpan kegiatan dan mulai terima pendaftaran</li>
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
                </div>
            </div>
        </div>
    </div>
</div>

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
