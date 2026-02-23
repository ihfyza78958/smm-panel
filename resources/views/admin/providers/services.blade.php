<x-admin-layout>
    <x-slot name="header">Provider Services</x-slot>

    <div x-data="providerServices()" x-init="fetchServices()" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $provider->domain }} — Services</h2>
                <p class="text-sm text-gray-500 mt-1">
                    API: <a href="{{ $provider->url }}" target="_blank" class="text-indigo-500 hover:underline">{{ $provider->url }}</a>
                    &bull; Currency: {{ $provider->currency }}
                    @if($provider->last_synced_at)
                        &bull; Last Synced: {{ $provider->last_synced_at->diffForHumans() }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.providers.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
                    &larr; Back
                </a>
                <button @click="fetchServices()" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition disabled:opacity-50 flex items-center gap-2">
                    <svg class="w-4 h-4" :class="loading && 'animate-spin'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="loading ? 'Fetching...' : 'Fetch from API'"></span>
                </button>
                <button @click="syncAll()" :disabled="syncing || !fetched" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium transition disabled:opacity-50 flex items-center gap-2">
                    <svg class="w-4 h-4" :class="syncing && 'animate-spin'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span x-text="syncing ? 'Syncing...' : 'Sync Imported'"></span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Balance</div>
                <div class="text-xl font-bold text-gray-900 mt-1" x-text="(stats.balance ?? '{{ number_format($provider->balance, 2) }}') + ' {{ $provider->currency }}'"></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Total Remote</div>
                <div class="text-xl font-bold text-blue-600 mt-1" x-text="stats.total ?? '—'"></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Imported</div>
                <div class="text-xl font-bold text-green-600 mt-1" x-text="stats.imported ?? '{{ $provider->imported_services }}'"></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Selected</div>
                <div class="text-xl font-bold text-indigo-600 mt-1" x-text="selectedCount"></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Price Changes</div>
                <div class="text-xl font-bold text-amber-600 mt-1" x-text="priceChangedCount"></div>
            </div>
        </div>

        <!-- Alerts -->
        <template x-if="error">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-semibold text-red-800">Error</p>
                    <p class="text-sm text-red-600" x-text="error"></p>
                </div>
            </div>
        </template>

        <template x-if="successMsg">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-green-700 font-medium" x-text="successMsg"></p>
            </div>
        </template>

        <!-- Filters & Import Controls -->
        <div x-show="fetched" x-transition class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="flex flex-wrap gap-3 items-center">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" x-model="search" placeholder="Search services..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-64">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- Filter -->
                    <select x-model="filterMode" class="border border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all">All Services</option>
                        <option value="not_imported">Not Imported</option>
                        <option value="imported">Already Imported</option>
                        <option value="price_changed">Price Changed</option>
                    </select>

                    <!-- Category Filter -->
                    <select x-model="filterCategory" class="border border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 max-w-[200px]">
                        <option value="all">All Categories</option>
                        <template x-for="cat in categoryNames" :key="cat">
                            <option :value="cat" x-text="cat"></option>
                        </template>
                    </select>
                </div>

                <!-- Bulk import controls -->
                <div class="flex gap-3 items-center flex-wrap">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 font-medium whitespace-nowrap">Profit Margin:</label>
                        <div class="relative">
                            <input type="number" x-model="profitMargin" min="0" max="500" step="1" class="w-20 border border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 font-medium whitespace-nowrap">Assign to:</label>
                        <select x-model="assignCategory" class="border border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 max-w-[180px]">
                            <option value="">Auto-create categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button @click="importSelected()" :disabled="importing || selectedCount === 0" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-semibold transition disabled:opacity-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span x-text="importing ? 'Importing...' : 'Import Selected (' + selectedCount + ')'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <template x-if="loading && !fetched">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
                <svg class="w-10 h-10 text-indigo-500 animate-spin mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <p class="text-gray-500 font-medium">Fetching services from API...</p>
                <p class="text-gray-400 text-sm mt-1">This may take a moment for large catalogs</p>
            </div>
        </template>

        <!-- Services by Category -->
        <template x-if="fetched">
            <div class="space-y-4">
                <template x-for="(catServices, catName) in filteredServices" :key="catName">
                    <div x-show="catServices.length > 0" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <!-- Category Header -->
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex items-center justify-between cursor-pointer"
                             @click="toggleCategory(catName)">
                            <div class="flex items-center gap-3">
                                <button @click.stop="selectCategory(catName)" class="text-indigo-600 hover:text-indigo-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </button>
                                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide" x-text="catName"></h3>
                                <span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full text-xs font-medium" x-text="catServices.length + ' services'"></span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="expandedCats[catName] && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>

                        <!-- Services Table -->
                        <div x-show="expandedCats[catName]" x-transition>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                                        <tr>
                                            <th class="px-4 py-3 w-10"><input type="checkbox" @change="toggleCategoryCheckbox(catName, $event)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"></th>
                                            <th class="px-4 py-3 text-left font-semibold w-16">ID</th>
                                            <th class="px-4 py-3 text-left font-semibold">Service</th>
                                            <th class="px-4 py-3 text-left font-semibold">Type</th>
                                            <th class="px-4 py-3 text-right font-semibold">Rate ({{ $provider->currency }})</th>
                                            <th class="px-4 py-3 text-center font-semibold">Min / Max</th>
                                            <th class="px-4 py-3 text-center font-semibold">Features</th>
                                            <th class="px-4 py-3 text-center font-semibold">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <template x-for="svc in catServices" :key="svc.service">
                                            <tr class="hover:bg-gray-50/50 transition" :class="svc.is_imported ? 'bg-green-50/30' : ''">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" 
                                                        :value="svc.service" 
                                                        :checked="selected[svc.service]"
                                                        @change="toggleService(svc)"
                                                        :disabled="svc.is_imported"
                                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-30">
                                                </td>
                                                <td class="px-4 py-3 font-mono text-xs text-gray-400" x-text="svc.service"></td>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900 text-xs leading-relaxed" x-text="svc.name"></div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600" x-text="svc.type || 'Default'"></span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <div class="font-bold text-gray-900" x-text="parseFloat(svc.rate).toFixed(4)"></div>
                                                    <template x-if="svc.is_imported && svc.price_changed">
                                                        <div class="text-xs text-amber-600 font-medium">
                                                            was <span x-text="parseFloat(svc.local_provider_rate).toFixed(4)"></span>
                                                        </div>
                                                    </template>
                                                </td>
                                                <td class="px-4 py-3 text-center text-xs text-gray-500">
                                                    <span x-text="svc.min"></span> - <span x-text="svc.max"></span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex justify-center gap-1.5">
                                                        <span x-show="svc.refill" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700" title="Refill">R</span>
                                                        <span x-show="svc.cancel" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700" title="Cancel">C</span>
                                                        <span x-show="svc.dripfeed" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700" title="Drip-feed">D</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <template x-if="svc.is_imported">
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold" :class="svc.price_changed ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'">
                                                            <span class="w-1.5 h-1.5 rounded-full" :class="svc.price_changed ? 'bg-amber-500' : 'bg-green-500'"></span>
                                                            <span x-text="svc.price_changed ? 'Price Changed' : 'Imported'"></span>
                                                        </span>
                                                    </template>
                                                    <template x-if="!svc.is_imported">
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                            Not Imported
                                                        </span>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty state -->
                <template x-if="Object.keys(filteredServices).length === 0 || allFilteredEmpty">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <p class="text-gray-500">No services match your filters.</p>
                    </div>
                </template>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
    function providerServices() {
        return {
            loading: false,
            fetched: false,
            importing: false,
            syncing: false,
            error: null,
            successMsg: null,
            services: {},
            selected: {},
            search: '',
            filterMode: 'all',
            filterCategory: 'all',
            profitMargin: 20,
            assignCategory: '',
            expandedCats: {},
            stats: {},

            get categoryNames() {
                return Object.keys(this.services).sort();
            },

            get selectedCount() {
                return Object.values(this.selected).filter(Boolean).length;
            },

            get priceChangedCount() {
                let count = 0;
                for (const cat of Object.values(this.services)) {
                    for (const svc of cat) {
                        if (svc.price_changed) count++;
                    }
                }
                return count;
            },

            get filteredServices() {
                const result = {};
                const searchLower = this.search.toLowerCase();

                for (const [catName, catServices] of Object.entries(this.services)) {
                    if (this.filterCategory !== 'all' && catName !== this.filterCategory) continue;

                    const filtered = catServices.filter(svc => {
                        // Search filter
                        if (searchLower && !svc.name.toLowerCase().includes(searchLower) && 
                            !String(svc.service).includes(searchLower)) {
                            return false;
                        }
                        // Status filter
                        if (this.filterMode === 'not_imported' && svc.is_imported) return false;
                        if (this.filterMode === 'imported' && !svc.is_imported) return false;
                        if (this.filterMode === 'price_changed' && !svc.price_changed) return false;
                        return true;
                    });

                    if (filtered.length > 0) {
                        result[catName] = filtered;
                    }
                }
                return result;
            },

            get allFilteredEmpty() {
                return Object.values(this.filteredServices).every(arr => arr.length === 0);
            },

            async fetchServices() {
                this.loading = true;
                this.error = null;
                this.successMsg = null;

                try {
                    const resp = await fetch('{{ route("admin.providers.fetch-services", $provider) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await resp.json();

                    if (!resp.ok) {
                        this.error = data.error || 'Failed to fetch services';
                        return;
                    }

                    this.services = data.services;
                    this.stats = { total: data.total, imported: data.imported, balance: data.balance };
                    this.fetched = true;

                    // Expand first 3 categories by default
                    const cats = Object.keys(this.services);
                    cats.slice(0, 3).forEach(c => this.expandedCats[c] = true);

                } catch (e) {
                    this.error = 'Network error: ' + e.message;
                } finally {
                    this.loading = false;
                }
            },

            toggleCategory(catName) {
                this.expandedCats[catName] = !this.expandedCats[catName];
            },

            toggleService(svc) {
                if (svc.is_imported) return;
                this.selected[svc.service] = !this.selected[svc.service];
            },

            selectCategory(catName) {
                const catServices = this.filteredServices[catName] || [];
                const notImported = catServices.filter(s => !s.is_imported);
                const allSelected = notImported.every(s => this.selected[s.service]);

                notImported.forEach(s => {
                    this.selected[s.service] = !allSelected;
                });
            },

            toggleCategoryCheckbox(catName, event) {
                const checked = event.target.checked;
                const catServices = this.filteredServices[catName] || [];
                catServices.filter(s => !s.is_imported).forEach(s => {
                    this.selected[s.service] = checked;
                });
            },

            async importSelected() {
                if (this.selectedCount === 0) return;

                this.importing = true;
                this.error = null;
                this.successMsg = null;

                // Build services array from selection
                const servicesToImport = [];
                for (const [catName, catServices] of Object.entries(this.services)) {
                    for (const svc of catServices) {
                        if (this.selected[svc.service]) {
                            servicesToImport.push({
                                id: svc.service,
                                name: svc.name,
                                category_name: catName,
                                category_id: this.assignCategory || null,
                                rate: svc.rate,
                                min: svc.min,
                                max: svc.max,
                                type: svc.type || null,
                                refill: svc.refill || false,
                                cancel: svc.cancel || false,
                                dripfeed: svc.dripfeed || false,
                                description: svc.description || null,
                            });
                        }
                    }
                }

                try {
                    const resp = await fetch('{{ route("admin.providers.import-services", $provider) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            services: servicesToImport,
                            profit_margin: this.profitMargin,
                        }),
                    });
                    const data = await resp.json();

                    if (!resp.ok) {
                        this.error = data.error || 'Import failed';
                        return;
                    }

                    this.successMsg = data.message;
                    this.selected = {};

                    // Re-fetch to update statuses
                    await this.fetchServices();

                } catch (e) {
                    this.error = 'Network error: ' + e.message;
                } finally {
                    this.importing = false;
                }
            },

            async syncAll() {
                this.syncing = true;
                this.error = null;
                this.successMsg = null;

                try {
                    const resp = await fetch('{{ route("admin.providers.sync-services", $provider) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });
                    const data = await resp.json();

                    if (!resp.ok) {
                        this.error = data.error || 'Sync failed';
                        return;
                    }

                    this.successMsg = data.message;

                    // Re-fetch to see updated data
                    await this.fetchServices();

                } catch (e) {
                    this.error = 'Network error: ' + e.message;
                } finally {
                    this.syncing = false;
                }
            },
        };
    }
    </script>
    @endpush
</x-admin-layout>
