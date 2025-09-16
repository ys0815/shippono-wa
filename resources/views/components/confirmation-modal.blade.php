@props([
    'id' => 'confirmation-modal',
    'title' => '確認',
    'message' => 'この操作を実行しますか？',
    'confirmText' => '実行',
    'cancelText' => 'キャンセル',
    'confirmClass' => 'bg-red-600 hover:bg-red-700 text-white',
    'icon' => '⚠️'
])

<div x-data="{ 
    open: false,
    formId: null,
    title: '{{ $title }}',
    message: '{{ $message }}',
    confirmText: '{{ $confirmText }}',
    cancelText: '{{ $cancelText }}',
    confirmClass: '{{ $confirmClass }}',
    icon: '{{ $icon }}',
    openModal(event) {
        console.log('Modal event received:', event.detail);
        if (event.detail && event.detail.id === '{{ $id }}') {
            this.formId = event.detail.formId;
            this.title = event.detail.title || '{{ $title }}';
            this.message = event.detail.message || '{{ $message }}';
            this.confirmText = event.detail.confirmText || '{{ $confirmText }}';
            this.cancelText = event.detail.cancelText || '{{ $cancelText }}';
            this.confirmClass = event.detail.confirmClass || '{{ $confirmClass }}';
            this.icon = event.detail.icon || '{{ $icon }}';
            this.open = true;
            console.log('Modal opened with data:', {
                formId: this.formId,
                title: this.title,
                message: this.message
            });
        }
    },
    confirmAction() {
        console.log('Confirm action triggered, formId:', this.formId);
        if (this.formId) {
            const form = document.getElementById(this.formId);
            if (form) {
                console.log('Submitting form:', form);
                form.submit();
            } else {
                console.error('Form not found with ID:', this.formId);
            }
        }
        this.open = false;
    },
    closeModal() {
        this.open = false;
    }
}" 
x-show="open" 
x-cloak
@open-confirm.window="openModal($event)"
@keydown.escape.window="closeModal()"
class="fixed inset-0 z-[9999] overflow-y-auto"
style="display: none;">

    <!-- 背景オーバーレイ -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()"></div>

    <!-- モーダルダイアログ -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            
            <!-- モーダル内容 -->
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- アイコン -->
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="text-2xl" x-text="icon"></span>
                    </div>
                    
                    <!-- テキスト内容 -->
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title" x-text="title">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="message">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ボタンエリア -->
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <!-- 実行ボタン -->
                <button type="button" 
                        @click="confirmAction()"
                        class="inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        :class="confirmClass"
                        x-text="confirmText">
                </button>
                
                <!-- キャンセルボタン -->
                <button type="button" 
                        @click="closeModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        x-text="cancelText">
                </button>
            </div>
        </div>
    </div>
</div>