<x-app-layout>
    <x-slot name="header">New Order</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
         x-data="orderForm"
         data-services-url="{{ route('orders.category-services', ['category' => 'CATEGORY_ID']) }}">

        <!-- Order Form -->
        <div class="lg:col-span-2">
            <div class="card relative overflow-hidden">

                <!-- Progress Bar -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gray-100">
                    <div class="h-full bg-indigo-600 transition-all duration-500" :style="'width:' + progress + '%'"></div>
                </div>

                @if(session('success'))
                    <div class="mb-4 mt-2 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 mt-2 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form action="{{ route('orders.store') }}" method="POST" class="space-y-5 mt-4">
                    @csrf

                    <!-- Category search + select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Platform / Category</label>
                        <input type="text" x-model="categorySearch" placeholder="Search category..."
                            class="input-field mb-2 text-sm"
                            autocomplete="off">
                        <select name="category_id" x-model="selectedCategory"
                            size="6"
                            class="input-field bg-gray-50 h-auto">
                            <option value="">— select a category —</option>
                            <template x-for="cat in filteredCategories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                        <p class="text-xs text-gray-400 mt-1" x-text="filteredCategories.length + ' categories'"></p>
                    </div>

                    <!-- Service -->
                    <div style="display:none" x-show="selectedCategory">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service</label>
                        <div class="relative">
                            <select name="service_id" x-model="selectedServiceId" @change="pickService"
                                :disabled="loadingServices"
                                class="input-field bg-gray-50 disabled:opacity-60">
                                <template x-if="loadingServices">
                                    <option>Loading...</option>
                                </template>
                                <template x-if="!loadingServices">
                                    <template x-if="services.length === 0">
                                        <option value="">No services found</option>
                                    </template>
                                </template>
                                <template x-if="!loadingServices && services.length > 0">
                                    <option value="">— select a service —</option>
                                </template>
                                <template x-for="svc in services" :key="svc.id">
                                    <option :value="svc.id" x-text="'#'+svc.id+' '+svc.name+' — NPR '+svc.price+'/1k'"></option>
                                </template>
                            </select>
                            <div x-show="loadingServices" class="absolute right-3 top-3 pointer-events-none">
                                <svg class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div style="display:none" x-show="currentService"
                            class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-800">
                            <p><strong>Min:</strong> <span x-text="currentService?.min"></span> &nbsp;|&nbsp; <strong>Max:</strong> <span x-text="currentService?.max"></span></p>
                            <p class="mt-1 text-xs text-blue-500" x-text="currentService?.desc || ''"></p>
                        </div>
                    </div>

                    <!-- Link -->
                    <div style="display:none" x-show="selectedServiceId">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Link / Username</label>
                        <input type="text" name="link" value="{{ old('link') }}" class="input-field"
                            placeholder="https://... or @username" required>
                    </div>

                    <!-- Quantity -->
                    <div style="display:none" x-show="selectedServiceId">
                        <div class="flex justify-between mb-2">
                            <label class="text-sm font-semibold text-gray-700">Quantity</label>
                            <button type="button" class="text-xs text-indigo-600 font-medium"
                                @click="quantity = currentService?.min">Set Min</button>
                        </div>
                        <input type="number" name="quantity" x-model="quantity" class="input-field"
                            :min="currentService?.min" :max="currentService?.max"
                            placeholder="e.g. 1000" required>
                        <p class="text-xs text-gray-400 mt-1" x-show="currentService">
                            Min: <span x-text="currentService?.min"></span> — Max: <span x-text="currentService?.max"></span>
                        </p>
                    </div>

                    <!-- Submit -->
                    <div style="display:none" x-show="selectedServiceId" class="pt-2">
                        <button type="submit" class="w-full btn-primary py-3 text-base shadow-lg shadow-indigo-200">
                            Place Order
                            <span x-show="parseFloat(totalPrice) > 0">&nbsp;(NPR <span x-text="totalPrice"></span>)</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card bg-gray-900 text-white border-0">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm text-gray-400">
                    <div class="flex justify-between">
                        <span>Service</span>
                        <span class="text-white font-medium text-right text-xs" x-text="currentService ? currentService.name : '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Rate / 1k</span>
                        <span class="text-white font-medium" x-text="currentService ? 'NPR ' + currentService.price : '—'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Quantity</span>
                        <span class="text-white font-medium" x-text="quantity || '—'"></span>
                    </div>
                    <div class="h-px bg-gray-700 my-2"></div>
                    <div class="flex justify-between text-lg font-bold text-white">
                        <span>Total</span>
                        <span>NPR <span x-text="totalPrice">0.00</span></span>
                    </div>
                </div>
            </div>

            <div class="card bg-orange-50 border-orange-100">
                <h3 class="text-sm font-bold text-orange-800 mb-3">Important Rules</h3>
                <ul class="space-y-2 text-xs text-orange-700 list-disc list-inside">
                    <li>Make sure your account is public.</li>
                    <li>Do not place duplicate orders for the same link.</li>
                    <li>Do not place a new order for the same link until the current one completes.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            // All categories passed from PHP (tiny — only id+name)
            allCategories: @json($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])),
            categorySearch: '',

            selectedCategory: '',
            selectedServiceId: '',
            services: [],
            loadingServices: false,
            currentService: null,
            quantity: '',
            _servicesUrl: '',

            init() {
                this._servicesUrl = this.$el.dataset.servicesUrl;
                this.$watch('selectedCategory', val => {
                    this.selectedServiceId = '';
                    this.currentService = null;
                    this.quantity = '';
                    this.services = [];
                    if (val) this.loadServices(val);
                });
            },

            get filteredCategories() {
                const q = this.categorySearch.toLowerCase().trim();
                if (!q) return this.allCategories;
                return this.allCategories.filter(c => c.name.toLowerCase().includes(q));
            },

            async loadServices(categoryId) {
                this.loadingServices = true;
                try {
                    const url = this._servicesUrl.replace('CATEGORY_ID', categoryId);
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    this.services = await res.json();
                } catch(e) {
                    console.error('Services load error:', e);
                    this.services = [];
                } finally {
                    this.loadingServices = false;
                }
            },

            pickService() {
                this.currentService = this.services.find(s => String(s.id) === String(this.selectedServiceId)) || null;
                if (this.currentService) this.quantity = this.currentService.min;
            },

            get totalPrice() {
                if (!this.currentService || !this.quantity) return '0.00';
                return ((this.currentService.price / 1000) * Number(this.quantity)).toFixed(2);
            },

            get progress() {
                return (this.selectedCategory ? 33 : 0)
                     + (this.selectedServiceId ? 33 : 0)
                     + (this.quantity ? 34 : 0);
            }
        }));
    });
    </script>
</x-app-layout>
