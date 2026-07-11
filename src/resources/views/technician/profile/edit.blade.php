@extends('layouts.app')
@section('title', 'Profil Saya — Voltfix')

@push('head')
<style>
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E5E7EB;
        border-radius: 12px;
        font-size: 14px;
        color: #111827;
        background: #FAFAFA;
        transition: all .2s;
        outline: none;
    }
    .form-input:focus {
        border-color: #F97316;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(249,115,22,.12);
    }
    .form-input:disabled {
        background: #F3F4F6;
        color: #6B7280;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto pb-10 space-y-5">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui data akun dan profil teknisi Anda.</p>
        </div>
        <a href="{{ route('technician.dashboard') }}"
           class="text-sm text-gray-500 hover:text-orange-600 transition-colors self-start">
            ← Kembali ke dashboard
        </a>
    </div>

    <form method="POST" action="{{ route('technician.profile.update') }}" enctype="multipart/form-data" class="space-y-4"
          x-data="{
              avatarPreview: '{{ $user->avatarUrl() }}',
              handleAvatar(e) {
                  const file = e.target.files[0];
                  if (!file) return;
                  this.avatarPreview = URL.createObjectURL(file);
              }
          }">
        @csrf
        @method('PUT')

        {{-- Foto profil --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50">
                <p class="text-sm font-bold text-gray-900">Foto Profil</p>
                <p class="text-xs text-gray-400">Foto ini tampil di navbar dan profil Anda</p>
            </div>
            <div class="p-5">
                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <div class="relative flex-shrink-0">
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" alt="Foto profil" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                        </template>
                        <template x-if="!avatarPreview">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-md">
                                <span class="text-white font-bold text-2xl">{{ $user->initials() }}</span>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 w-full space-y-3">
                        <label class="block cursor-pointer">
                            <input type="file" name="avatar" accept="image/jpeg,image/jpg,image/png,image/webp" class="sr-only" @change="handleAvatar($event)">
                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-orange-700 bg-orange-50 border border-orange-200 hover:bg-orange-100 px-4 py-2 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Pilih Foto Baru
                            </span>
                        </label>
                        <p class="text-xs text-gray-400">JPG, PNG, atau WEBP · maks 2MB · disarankan foto wajah jelas</p>
                        @if($user->avatar_url)
                        <label class="inline-flex items-center gap-2 text-xs text-red-500 cursor-pointer">
                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-400"
                                   @change="if ($event.target.checked) avatarPreview = null">
                            Hapus foto profil
                        </label>
                        @endif
                        @error('avatar') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Akun --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50">
                <p class="text-sm font-bold text-gray-900">Data Akun</p>
                <p class="text-xs text-gray-400">Nama, email, dan kontak WhatsApp</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label for="name" class="text-xs font-semibold text-gray-600 mb-1.5 block">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="text-xs font-semibold text-gray-600 mb-1.5 block">Email <span class="text-red-400">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                        @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="text-xs font-semibold text-gray-600 mb-1.5 block">No. WhatsApp <span class="text-red-400">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="form-input" placeholder="628xxxxxxxxxx">
                        @error('phone') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50">
                <p class="text-sm font-bold text-gray-900">Ganti Password</p>
                <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah password</p>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="text-xs font-semibold text-gray-600 mb-1.5 block">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 8 karakter">
                    @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="text-xs font-semibold text-gray-600 mb-1.5 block">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                </div>
            </div>
        </div>

        {{-- Profil teknisi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50">
                <p class="text-sm font-bold text-gray-900">Profil Teknisi</p>
                <p class="text-xs text-gray-400">Keahlian diatur admin, Anda bisa ubah pengalaman & ketersediaan</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Keahlian Utama</label>
                    <input type="text" disabled class="form-input"
                           value="{{ \App\Helpers\CategoryHelper::label($technician->skill_category) }}">
                    <p class="text-xs text-gray-400 mt-1">Hubungi admin jika keahlian perlu diubah.</p>
                </div>
                <div>
                    <label for="experience" class="text-xs font-semibold text-gray-600 mb-1.5 block">Pengalaman</label>
                    <input type="text" id="experience" name="experience" value="{{ old('experience', $technician->experience) }}" class="form-input" placeholder="Contoh: 5 tahun servis laptop">
                    @error('experience') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-3 cursor-pointer bg-stone-50 border border-stone-100 rounded-xl px-4 py-3">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1"
                           class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400"
                           {{ (string) old('is_available', $technician->is_available ? '1' : '0') === '1' ? 'checked' : '' }}>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Status tersedia</p>
                        <p class="text-xs text-gray-500">Matikan saat cuti atau tidak bisa menerima tugas baru</p>
                    </div>
                </label>
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
                    <span class="text-lg">⭐</span>
                    <div>
                        <p class="text-xs text-amber-700 font-semibold">Rating rata-rata</p>
                        <p class="text-sm font-bold text-amber-900">{{ number_format($technician->average_rating, 1) }} / 5.0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('technician.dashboard') }}"
               class="px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 rounded-xl transition-all">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-200 transition-all text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
