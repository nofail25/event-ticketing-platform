@if(session('success') || session('danger') || session('warning') || session('info'))
    <div x-data="{ 
            show: true,
            type: '{{ session('success') ? 'success' : (session('danger') ? 'danger' : (session('warning') ? 'warning' : 'info')) }}',
            message: '{{ session('success') ?? session('danger') ?? session('warning') ?? session('info') }}',
            title: '{{ session('success') ? 'Berhasil' : (session('danger') ? 'Error' : (session('warning') ? 'Peringatan' : 'Informasi')) }}'
         }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out-ui duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 sm:top-4 sm:bottom-auto sm:right-6 z-[100] max-w-sm w-full shadow-2xl rounded-2xl overflow-hidden border border-slate-100"
         style="display: none;"
    >
        <div class="bg-white p-4 sm:p-5 relative flex items-start gap-4">
            <!-- Icon -->
            <div class="shrink-0">
                <template x-if="type === 'success'">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <span class="material-symbols-outlined text-xl" style="line-height:1;">check_circle</span>
                    </div>
                </template>
                <template x-if="type === 'danger'">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                        <span class="material-symbols-outlined text-xl" style="line-height:1;">cancel</span>
                    </div>
                </template>
                <template x-if="type === 'warning'">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <span class="material-symbols-outlined text-xl" style="line-height:1;">warning</span>
                    </div>
                </template>
                <template x-if="type === 'info'">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                        <span class="material-symbols-outlined text-xl" style="line-height:1;">info</span>
                    </div>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 pt-0.5">
                <h3 class="font-bold text-slate-900 text-sm" x-text="title"></h3>
                <p class="mt-1 text-sm text-slate-600 leading-relaxed" x-text="message"></p>
                

            </div>

            <!-- Close Button -->
            <button @click="show = false" class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors focus:outline-none">
                <span class="material-symbols-outlined text-lg" style="line-height:1;">close</span>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="h-1 w-full bg-slate-100">
            <div class="h-full animate-progress" :class="{
                'bg-emerald-500': type === 'success',
                'bg-rose-500': type === 'danger',
                'bg-amber-500': type === 'warning',
                'bg-blue-500': type === 'info',
            }" style="animation-duration: 5s; animation-timing-function: linear; animation-fill-mode: forwards;"></div>
        </div>
    </div>
@endif

<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-progress {
    animation-name: progress;
}
</style>
