@extends('layouts.customer_premium')

@section('title', 'Chat Pesanan #' . $order->order_number)

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            const chatForm = document.getElementById('chat-form');
            const messageInput = document.getElementById('message-input');
            const orderId = "{{ $order->id }}";
            let lastMessageCount = 0;

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function fetchMessages() {
                fetch(`/chat/${orderId}/messages`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages) {
                            let html = '';
                            data.messages.forEach(msg => {
                                const isMe = msg.sender_type === 'pelanggan';
                                const bubbles = [];
                                
                                if (msg.message) {
                                    bubbles.push({
                                        type: 'text',
                                        html: `<p class="text-[14px] font-bold leading-relaxed whitespace-pre-wrap">${escapeHtml(msg.message)}</p>`
                                    });
                                }
                                
                                if (msg.image) {
                                    bubbles.push({
                                        type: 'image',
                                        html: `
                                            <div class="rounded-xl overflow-hidden max-w-xs cursor-zoom-in" onclick="window.open('${msg.image}', '_blank')">
                                                <img src="${msg.image}" alt="Chat Image" class="w-full max-h-60 object-cover hover:scale-[1.02] transition-transform">
                                            </div>
                                        `
                                    });
                                }

                                bubbles.forEach((bubble, idx) => {
                                    const isLast = idx === bubbles.length - 1;
                                    const marginBottom = isLast ? 'mb-4' : 'mb-1.5';
                                    
                                    if (isMe) {
                                        const paddingClass = bubble.type === 'image' 
                                            ? 'p-1.5 bg-emerald-950 text-white rounded-[20px] border border-emerald-900/10' 
                                            : 'bg-emerald-950 text-white rounded-[24px] px-6 py-4';
                                        const roundClass = isLast ? 'rounded-tr-none' : '';
                                        
                                        html += `
                                            <div class="flex justify-end ${marginBottom} animate-in fade-in slide-in-from-right-4 duration-300">
                                                <div class="max-w-[70%] flex flex-col items-end">
                                                    <div class="${paddingClass} ${roundClass} shadow-md">
                                                        ${bubble.html}
                                                    </div>
                                                    ${isLast ? `<p class="text-[10px] text-emerald-900/40 font-bold uppercase tracking-widest text-right mt-1.5">${msg.time}</p>` : ''}
                                                </div>
                                            </div>
                                        `;
                                    } else {
                                        const paddingClass = bubble.type === 'image' 
                                            ? 'p-1.5 bg-white border border-emerald-100 text-emerald-955 rounded-[20px]' 
                                            : 'bg-white border border-emerald-100 text-emerald-955 rounded-[24px] px-6 py-4';
                                        const roundClass = isLast ? 'rounded-tl-none' : '';
                                        
                                        html += `
                                            <div class="flex justify-start ${marginBottom} animate-in fade-in slide-in-from-left-4 duration-300">
                                                <div class="max-w-[70%] flex flex-col items-start">
                                                    <div class="${paddingClass} ${roundClass} shadow-sm">
                                                        ${bubble.html}
                                                    </div>
                                                    ${isLast ? `<p class="text-[10px] text-emerald-900/40 font-bold uppercase tracking-widest text-left mt-1.5">${msg.time} • Admin</p>` : ''}
                                                </div>
                                            </div>
                                        `;
                                    }
                                });
                            });
                            
                            chatMessages.innerHTML = html;
                            
                            if (data.messages.length > lastMessageCount) {
                                scrollToBottom();
                                lastMessageCount = data.messages.length;
                            }
                        }
                    })
                    .catch(err => console.error('Error fetching messages:', err));
            }

            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            const uploadTriggerBtn = document.getElementById('upload-trigger-btn');
            const imageFileInput = document.getElementById('image-file-input');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const imagePreviewName = document.getElementById('image-preview-name');
            const imagePreviewSize = document.getElementById('image-preview-size');
            const removeImageBtn = document.getElementById('remove-image-btn');

            uploadTriggerBtn.addEventListener('click', function() {
                imageFileInput.click();
            });

            imageFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran gambar melebihi batas 2 MB');
                        this.value = '';
                        imagePreviewContainer.classList.add('hidden');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewName.textContent = file.name;
                        imagePreviewSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                        imagePreviewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreviewContainer.classList.add('hidden');
                }
            });

            removeImageBtn.addEventListener('click', function() {
                imageFileInput.value = '';
                imagePreviewContainer.classList.add('hidden');
            });

            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                const file = imageFileInput.files[0];

                if (!message && !file) {
                    alert('pesan atau gambar tidak boleh kosong');
                    return;
                }

                const formData = new FormData();
                if (message) {
                    formData.append('message', message);
                }
                if (file) {
                    formData.append('image', file);
                }

                messageInput.value = '';
                imageFileInput.value = '';
                imagePreviewContainer.classList.add('hidden');
                
                fetch(`/pelanggan/chat/${orderId}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        fetchMessages();
                    }
                })
                .catch(err => {
                    console.error('Error sending message:', err);
                    if (err.errors && err.errors.image) {
                        alert(err.errors.image[0]);
                    } else if (err.error) {
                        alert(err.error);
                    } else {
                        alert('Gagal mengirim pesan.');
                    }
                });
            });

            // Initial fetch and start polling
            fetchMessages();
            setInterval(fetchMessages, 3000);
            
            setTimeout(scrollToBottom, 500);
        });
    </script>
@endsection

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('/pelanggan/riwayat') }}" class="w-12 h-12 bg-white border border-emerald-100 rounded-2xl flex items-center justify-center text-emerald-950 hover:bg-emerald-50 hover:scale-105 transition-all shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
            </a>
            <div>
                <h1 class="text-[28px] md:text-[36px] font-black text-emerald-950 tracking-tighter leading-none mb-1.5">Chat Pesanan</h1>
                <p class="text-emerald-700/60 font-bold text-[14px]">Diskusikan pesanan Anda langsung dengan tim kami</p>
            </div>
        </div>
        
        <span class="px-5 py-2.5 bg-emerald-950 text-white text-[12px] font-black rounded-full uppercase tracking-wider shadow-md">
            Order ID: #{{ $order->order_number }}
        </span>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-12">
        <!-- Sidebar: Order details -->
        <div class="bg-white rounded-[40px] border border-emerald-100 shadow-xl overflow-hidden p-8 space-y-6 lg:col-span-1">
            <div class="flex items-center gap-3 pb-4 border-b border-emerald-50">
                <div class="w-2 h-6 bg-emerald-950 rounded-full"></div>
                <h4 class="text-[14px] font-black text-emerald-950 uppercase tracking-[0.2em]">Info Pesanan</h4>
            </div>

            <!-- Order status and total -->
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block mb-1">Status</span>
                    @php
                        $statusColors = [
                            'Pending' => 'bg-amber-500',
                            'Verifikasi' => 'bg-emerald-500',
                            'Sedang Dikemas' => 'bg-purple-500',
                            'Sedang Dikirim' => 'bg-blue-500',
                            'Selesai' => 'bg-emerald-500',
                            'Dibatalkan' => 'bg-rose-500',
                            'Dikembalikan' => 'bg-rose-600',
                            'Pengajuan Pending' => 'bg-amber-500',
                            'Pengembalian Ditolak' => 'bg-rose-600',
                        ];
                        $statusColor = $statusColors[$order->status] ?? 'bg-slate-500';
                    @endphp
                    <span class="px-3.5 py-1.5 {{ $statusColor }} text-white text-[10px] font-black rounded-full uppercase tracking-widest shadow-sm inline-block">{{ $order->status }}</span>
                </div>
                
                <div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block mb-1">Total Pembayaran</span>
                    <span class="text-[20px] font-black text-emerald-950 tracking-tighter">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block mb-1">Waktu Pesanan</span>
                    <p class="text-[13px] font-bold text-emerald-950 leading-relaxed">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <!-- Books List -->
            <div class="pt-6 border-t border-emerald-50 space-y-4">
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block">Daftar Buku</span>
                <div class="space-y-3 max-h-[220px] overflow-y-auto pr-1">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3 bg-emerald-50/20 p-3 rounded-2xl border border-emerald-950/5">
                            <div class="w-10 h-14 rounded-lg overflow-hidden shrink-0 shadow-sm">
                                <img src="{{ $item->book->image }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0 flex-grow">
                                <h5 class="text-[13px] font-black text-emerald-950 truncate leading-tight">{{ $item->book->title }}</h5>
                                <p class="text-[11px] font-bold text-emerald-600 mt-0.5">{{ $item->quantity }} Pcs x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="pt-6 border-t border-emerald-50 space-y-2">
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block">Alamat Pengiriman</span>
                <p class="text-[12px] font-bold text-emerald-950/80 leading-relaxed italic">{{ $order->address ?? 'Alamat tidak tersedia' }}</p>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-2 bg-white rounded-[40px] border border-emerald-100 shadow-xl overflow-hidden flex flex-col h-[600px]">
            <!-- Chat Header -->
            <div class="px-8 py-5 border-b border-emerald-50 bg-emerald-50/20 flex items-center gap-4">
                <div class="w-11 h-11 bg-emerald-950 text-white rounded-full flex items-center justify-center font-black text-[14px] shadow-md uppercase">
                    AD
                </div>
                <div>
                    <h3 class="text-[16px] font-black text-emerald-950 tracking-tight leading-tight">Layanan Admin CIVAD</h3>
                    <p class="text-[11px] text-emerald-500 font-bold uppercase tracking-wider flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
                    </p>
                </div>
            </div>

            <!-- Messages Box -->
            <div id="chat-messages" class="flex-grow p-8 overflow-y-auto bg-emerald-50/10 custom-scrollbar flex flex-col">
                <div class="m-auto text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-emerald-200 mx-auto mb-4 animate-bounce"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" /></svg>
                    <p class="text-emerald-950 font-black">Memuat percakapan...</p>
                </div>
            </div>

            <!-- Input Form -->
            <div class="p-6 border-t border-emerald-50 bg-white">
                <form id="chat-form" class="flex flex-col gap-3">
                    <!-- Image Preview Container -->
                    <div id="image-preview-container" class="hidden bg-emerald-50/50 border-2 border-dashed border-emerald-950/20 rounded-2xl p-3 flex items-center gap-3 relative">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-emerald-900/10 shrink-0 relative">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow min-w-0">
                            <p id="image-preview-name" class="text-[13px] font-black text-emerald-950 truncate"></p>
                            <p id="image-preview-size" class="text-[11px] font-bold text-emerald-600/70"></p>
                        </div>
                        <button type="button" id="remove-image-btn" class="w-8 h-8 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Add File Button -->
                        <button type="button" id="upload-trigger-btn" class="w-14 h-14 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-950 rounded-2xl flex items-center justify-center transition-all shrink-0 active:scale-95 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        </button>
                        <input type="file" id="image-file-input" accept="image/*" class="hidden">
                        
                        <input type="text" id="message-input" autocomplete="off" placeholder="Tulis pesan Anda mengenai pesanan ini..." 
                               class="flex-grow bg-emerald-50/50 border-2 border-emerald-950/10 focus:border-emerald-950 rounded-2xl py-4 px-6 text-[15px] font-bold text-emerald-950 focus:outline-none transition-all placeholder:text-emerald-950/20">
                        <button type="submit" class="px-6 h-14 bg-emerald-950 hover:bg-emerald-900 text-white rounded-2xl flex items-center justify-center font-bold text-[15px] shadow-xl active:scale-95 transition-all shrink-0">
                            kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
