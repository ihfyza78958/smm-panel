<x-app-layout>
    <x-slot name="header">New Order</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
         x-data="orderForm"
         data-services-url="{{ route('orders.category-services', ['category' => 'CATEGORY_ID']) }}">

        <!-- Order Form -->
        <div class="lg:col-span-2 relative z-20">
            <div class="card relative overflow-visible z-10">

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

                    <!-- Category selection (Select2) -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Platform / Category</label>
                        <select name="category_id" id="category_select" x-model="selectedCategory" class="w-full select2-styled" style="width: 100%">
                            <option value="">— Select a category —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service (Select2) -->
                    <div style="display:none" id="service_container" x-show="selectedCategory" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service <span x-show="loadingServices" class="text-indigo-500 text-xs ml-2">(Loading...)</span></label>
                        <select name="service_id" id="service_select" x-model="selectedServiceId" class="w-full select2-styled" style="width: 100%" :disabled="loadingServices">
                            <option value="">— Select a category first —</option>
                        </select>
                        
                        <div style="display:none" x-show="currentService"
                            class="mt-3 p-4 bg-indigo-50 rounded-lg border border-indigo-100 text-sm text-indigo-900 shadow-sm">
                            <div class="flex gap-4 mb-2">
                                <p class="bg-white px-3 py-1 rounded shadow-sm"><strong>Min:</strong> <span x-text="currentService?.min"></span></p>
                                <p class="bg-white px-3 py-1 rounded shadow-sm"><strong>Max:</strong> <span x-text="currentService?.max"></span></p>
                            </div>
                            <p class="mt-2 text-xs text-indigo-700 font-medium" x-text="currentService?.desc || ''"></p>
                        </div>
                    </div>

                    <!-- Link -->
                    <div style="display:none" x-show="selectedServiceId" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Link / Username</label>
                        <input type="text" name="link" value="{{ old('link') }}" class="input-field"
                            placeholder="https://... or @username" required>
                    </div>

                    <!-- Quantity -->
                    <div style="display:none" x-show="selectedServiceId" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
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

    
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        .select2-container--default .select2-selection--single {
            height: 46px;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 10px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            font-size: 0.875rem;
            padding-left: 1rem;
        }
        .select2-dropdown {
            border-color: #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .select2-search__field {
            border-radius: 0.375rem !important;
        }
        .select2-results__option {
            padding: 8px 16px;
            font-size: 0.875rem;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #4f46e5;
        }
    </style>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Select2 on both dropdowns
        $("#category_select").select2({
            placeholder: "— Select a category —",
            width: "100%"
        });
        
        $("#service_select").select2({
            placeholder: "— Select a service —",
            width: "100%"
        });

        // Listen for Select2 changes and sync with Alpine data model
        $("#category_select").on("change", function() {
            let val = $(this).val();
            // Dispatch a native DOM event that Alpine can catch via @change or we can update directly
            let el = document.getElementById("category_select");
            el.dispatchEvent(new Event("input", { bubbles: true }));
            el.dispatchEvent(new Event("change", { bubbles: true }));
            
            // Alpine 3 safe data update
            if (typeof Alpine !== "undefined") {
                let alpineData = Alpine.$data(document.querySelector("[x-data='orderForm']"));
                if (alpineData) alpineData.selectedCategory = val;
            }
        });
        
        $("#service_select").on("change", function() {
            let val = $(this).val();
            let el = document.getElementById("service_select");
            el.dispatchEvent(new Event("input", { bubbles: true }));
            el.dispatchEvent(new Event("change", { bubbles: true }));
            
            if (typeof Alpine !== "undefined") {
                let alpineData = Alpine.$data(document.querySelector("[x-data='orderForm']"));
                if (alpineData) {
                    alpineData.selectedServiceId = val;
                    alpineData.pickService();
                }
            }
        });
    });
    </script>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            // All categories passed from PHP (tiny — only id+name)
            allCategories: @json($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])),
            categorySearch: '',

            selectedCategory: '',
            selectedCategoryName: '',
            
            selectCategory(cat) {
                this.selectedCategory = cat.id;
                this.selectedCategoryName = cat.name;
            },
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
                    
                    // Populate Select2 Service Dropdown
                    let svcOptions = '<option value="">— Select a service —</option>';
                    this.services.forEach(s => {
                        svcOptions += `<option value="${s.id}">#${s.id} ${s.name} — NPR ${s.price}/1k</option>`;
                    });
                    $('#service_select').html(svcOptions).trigger('change');
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

            get selectedServiceLabel() {
                if (!this.currentService) return '';
                return `#${this.currentService.id} ${this.currentService.name} - NPR ${this.currentService.price}/1k`;
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
