<div>
    {{-- Modal 1: ระบุเหตุผล --}}
    @if($showStep1 && $carModelId)
    <div
        wire:key="delete-step-1-{{ $carModelId }}"
        class="fixed inset-0 z-99999 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 50)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Overlay --}}
        <div
            wire:click="cancel"
            class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"
        ></div>

        {{-- Modal --}}
        <div 
            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-100000 overflow-hidden"
            x-show="show"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        >
            {{-- Header --}}
            <div class="bg-linear-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">
                            ระบุเหตุผลการลบ
                        </h3>
                        @if($carModelName)
                        <p class="text-red-100 text-sm mt-0.5">
                            {{ $carModelName }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-5">
                {{-- Reason Select --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        เหตุผลการลบ <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            wire:model.live="deletionReason"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900 font-medium
                                   focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10 
                                   transition-all duration-200 appearance-none cursor-pointer"
                        >
                            <option value="">-- เลือกเหตุผล --</option>
                            <option value="ยกเลิกการผลิต">ยกเลิกการผลิต</option>
                            <option value="ข้อมูลซ้ำ">ข้อมูลซ้ำ</option>
                            <option value="ข้อมูลไม่ถูกต้อง">ข้อมูลไม่ถูกต้อง</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('deletionReason')
                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Detail Textarea --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        รายละเอียดเพิ่มเติม
                        <span class="text-gray-400 font-normal">(ไม่บังคับ)</span>
                    </label>
                    <textarea
                        wire:model.live="deletionDetail"
                        rows="3"
                        placeholder="ระบุรายละเอียดเพิ่มเติม..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-900
                               focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10 
                               transition-all duration-200 resize-none"
                    ></textarea>
                    @error('deletionDetail')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button
                    wire:click="cancel"
                    type="button"
                    class="px-5 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-700 font-semibold 
                           hover:bg-gray-100 hover:border-gray-400 active:scale-95
                           transition-all duration-200"
                >
                    ยกเลิก
                </button>

                <button
                    wire:click="proceedToConfirm"
                    type="button"
                    class="px-5 py-2.5 rounded-xl bg-linear-to-r from-red-500 to-red-600 text-white font-semibold 
                           hover:from-red-600 hover:to-red-700 active:scale-95
                           shadow-lg shadow-red-500/30 hover:shadow-red-500/40
                           transition-all duration-200 flex items-center gap-2"
                >
                    ถัดไป
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal 2: ยืนยันการลบ --}}
    @if($showStep2 && $carModelId)
    <div
        wire:key="delete-step-2-{{ $carModelId }}"
        class="fixed inset-0 z-99999 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 50)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"></div>

        {{-- Modal --}}
        <div 
            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-100000 overflow-hidden"
            x-show="show"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        >
            {{-- Warning Icon Header --}}
            <div class="pt-8 pb-2 flex justify-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 pb-6 text-center">
                <h3 class="text-xl font-bold text-gray-900 mt-4">
                    ยืนยันการลบ
                </h3>

                @if($carModelName)
                <p class="mt-3 text-gray-600">
                    คุณต้องการลบ
                </p>
                <p class="text-lg font-bold text-red-600 mt-1">
                    {{ $carModelName }}
                </p>
                <p class="text-gray-600">
                    ใช่หรือไม่?
                </p>

                {{-- Summary Card --}}
                <div class="mt-5 p-4 rounded-xl bg-linear-to-br from-gray-50 to-gray-100 border border-gray-200 text-left">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500">เหตุผล</p>
                            <p class="font-semibold text-gray-900">{{ $deletionReason }}</p>
                        </div>
                    </div>

                    @if($deletionDetail)
                    <div class="flex items-start gap-3 mt-3 pt-3 border-t border-gray-200">
                        <div class="shrink-0 w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500">รายละเอียด</p>
                            <p class="text-gray-900">{{ $deletionDetail }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Warning Text --}}
                <p class="mt-4 text-sm text-red-500 font-medium">
                    การดำเนินการนี้ไม่สามารถย้อนกลับได้
                </p>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between gap-3">
                <button
                    wire:click="backToStep1"
                    type="button"
                    class="px-4 py-2.5 rounded-xl text-gray-600 font-semibold 
                           hover:bg-gray-200 active:scale-95
                           transition-all duration-200 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    ย้อนกลับ
                </button>

                <div class="flex gap-3">
                    <button
                        wire:click="cancel"
                        type="button"
                        class="px-5 py-2.5 border-2 border-gray-300 rounded-xl bg-white text-gray-700 font-semibold 
                               hover:bg-gray-100 hover:border-gray-400 active:scale-95
                               transition-all duration-200"
                    >
                        ยกเลิก
                    </button>

                    <button
                        wire:click="confirmDelete"
                        wire:loading.attr="disabled"
                        wire:target="confirmDelete"
                        type="button"
                        class="px-5 py-2.5 rounded-xl bg-linear-to-r from-red-500 to-red-600 text-white font-bold 
                               hover:from-red-600 hover:to-red-700 active:scale-95
                               shadow-lg shadow-red-500/30 hover:shadow-red-500/40
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100
                               transition-all duration-200 flex items-center gap-2"
                    >
                        <span wire:loading.remove wire:target="confirmDelete" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            ยืนยันการลบ
                        </span>
                        <span wire:loading wire:target="confirmDelete" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            กำลังลบ...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>