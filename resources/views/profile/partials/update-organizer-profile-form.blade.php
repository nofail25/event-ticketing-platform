<section>
    @php
        $profile = auth()->user()->organizerProfile ?? new \App\Models\OrganizerProfile();
    @endphp

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profil Organizer & Rekening Bank') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi identitas organizer, dokumen legal, dan rincian bank Anda untuk proses penarikan dana.") }}
        </p>
    </header>

    <div class="mt-6">
        @if ($profile->verification_status === 'verified')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative mb-6 text-sm">
                <strong>Status: Terverifikasi!</strong> Profil Anda sudah disetujui. Data utama tidak dapat diubah tanpa menghubungi admin.
            </div>
        @elseif ($profile->verification_status === 'pending')
            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl relative mb-6 text-sm">
                <strong>Status: Menunggu Verifikasi.</strong> Profil Anda sedang ditinjau oleh Admin. Anda akan bisa membuat Event setelah disetujui.
            </div>
        @elseif ($profile->verification_status === 'rejected')
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative mb-6 text-sm">
                <strong>Status: Ditolak.</strong> Profil Anda ditolak. Silakan perbarui data dengan benar dan submit ulang.
            </div>
        @else
            <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-xl relative mb-6 text-sm">
                <strong>Status: Belum Terverifikasi.</strong> Silakan lengkapi profil di bawah ini agar Anda bisa mulai membuat Event.
            </div>
        @endif

        <form action="{{ route('organizer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="company_name" :value="__('Nama Perusahaan / Organizer *')" />
                <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $profile->company_name)" required :readonly="$profile->verification_status === 'verified'" />
                <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="pic_name" :value="__('Nama Penanggung Jawab (PIC) *')" />
                    <x-text-input id="pic_name" name="pic_name" type="text" class="mt-1 block w-full" :value="old('pic_name', $profile->pic_name)" required :readonly="$profile->verification_status === 'verified'" />
                    <x-input-error class="mt-2" :messages="$errors->get('pic_name')" />
                </div>
                
                <div>
                    <x-input-label for="phone_number" :value="__('Nomor Telepon / WhatsApp *')" />
                    <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $profile->phone_number)" required :readonly="$profile->verification_status === 'verified'" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                </div>
            </div>

            <div>
                <x-input-label for="company_description" :value="__('Deskripsi')" />
                <textarea id="company_description" name="company_description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" {{ $profile->verification_status === 'verified' ? 'readonly' : '' }}>{{ old('company_description', $profile->company_description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('company_description')" />
            </div>

            <div>
                <x-input-label for="website_url" :value="__('Tautan Instagram / Website (Opsional)')" />
                <x-text-input id="website_url" name="website_url" type="url" placeholder="https://..." class="mt-1 block w-full" :value="old('website_url', $profile->website_url)" :readonly="$profile->verification_status === 'verified'" />
                <x-input-error class="mt-2" :messages="$errors->get('website_url')" />
            </div>

            <div>
                <x-input-label for="legal_document" :value="__('Dokumen KTP Penanggung Jawab *')" />
                @if ($profile->legal_document_path)
                    <p class="text-sm text-gray-600 mb-2 mt-1">
                        Dokumen saat ini: <a href="{{ Storage::url($profile->legal_document_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-semibold underline">Lihat Dokumen</a>
                    </p>
                @endif
                
                @if ($profile->verification_status !== 'verified')
                    <input type="file" id="legal_document" name="legal_document" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG. Maksimal 5MB.</p>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('legal_document')" />
            </div>

            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-bold mb-4 text-gray-900">Rekening Bank untuk Penarikan Dana</h3>

                <div class="space-y-6">
                    <div>
                        <x-input-label for="bank_name" :value="__('Nama Bank *')" />
                        <x-text-input id="bank_name" name="bank_name" type="text" placeholder="Misal: BCA, Mandiri, BNI" class="mt-1 block w-full" :value="old('bank_name', $profile->bank_name)" required :readonly="$profile->verification_status === 'verified'" />
                        <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                    </div>

                    <div>
                        <x-input-label for="account_number" :value="__('Nomor Rekening *')" />
                        <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full" :value="old('account_number', $profile->account_number)" required :readonly="$profile->verification_status === 'verified'" />
                        <x-input-error class="mt-2" :messages="$errors->get('account_number')" />
                    </div>

                    <div>
                        <x-input-label for="account_holder" :value="__('Atas Nama *')" />
                        <x-text-input id="account_holder" name="account_holder" type="text" class="mt-1 block w-full" :value="old('account_holder', $profile->account_holder)" required :readonly="$profile->verification_status === 'verified'" />
                        <x-input-error class="mt-2" :messages="$errors->get('account_holder')" />
                    </div>
                </div>
            </div>

            @if ($profile->verification_status !== 'verified')
                <div class="flex items-center gap-4 mt-6">
                    <button type="submit" class="primary-button">{{ __('Simpan & Ajukan Verifikasi') }}</button>

                    @if (session('status') === 'organizer-profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-emerald-600 font-bold"
                        >{{ __('Tersimpan.') }}</p>
                    @endif
                </div>
            @endif
        </form>
    </div>
</section>
