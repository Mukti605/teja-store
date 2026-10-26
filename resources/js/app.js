import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const cartAddUrl = () => document.querySelector('meta[name="cart-add-url"]')?.content ?? '/keranjang/tambah';

/* ---------- Store global: jumlah keranjang + notifikasi (toast) ---------- */
Alpine.store('cart', {
    count: 0,
    bumped: false,
    toast: { show: false, message: '', type: 'ok' },
    _timer: null,

    notify(message, type = 'ok') {
        this.toast = { show: true, message, type };
        clearTimeout(this._timer);
        this._timer = setTimeout(() => (this.toast.show = false), 2800);
    },

    bump() {
        this.bumped = true;
        setTimeout(() => (this.bumped = false), 350);
    },

    async add(productId, qty = 1) {
        try {
            const res = await fetch(cartAddUrl(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                },
                body: JSON.stringify({ product_id: productId, qty }),
            });
            const data = await res.json().catch(() => ({}));

            if (typeof data.count === 'number') this.count = data.count;

            if (res.ok) {
                this.bump();
                this.notify(data.message ?? 'Masuk ke keranjang.', 'ok');
                return true;
            }

            this.notify(data.message ?? 'Gagal menambahkan ke keranjang.', 'error');
            return false;
        } catch (e) {
            this.notify('Koneksi bermasalah. Coba lagi sebentar.', 'error');
            return false;
        }
    },
});

/* ---------- Halaman toko: filter, urut, dan modal detail (semua di browser) ---------- */
Alpine.data('shop', (products) => ({
    products,
    category: 'semua',
    brand: '',
    q: '',
    sort: 'rekomendasi',
    selected: null,
    qty: 1,
    busy: null,

    get filtered() {
        const term = this.q.trim().toLowerCase();
        const list = this.products.filter(
            (p) =>
                (this.category === 'semua' || p.category === this.category) &&
                (!this.brand || p.brand === this.brand) &&
                (!term || (p.name + ' ' + p.brand).toLowerCase().includes(term)),
        );
        const sorters = {
            rekomendasi: (a, b) => a.id - b.id,
            termurah: (a, b) => a.price - b.price,
            termahal: (a, b) => b.price - a.price,
            nama: (a, b) => a.name.localeCompare(b.name),
        };
        return list.sort(sorters[this.sort]);
    },

    // id produk -> urutan tampil (dipakai CSS "order" agar kartu bisa diurutkan tanpa dirender ulang)
    get orderMap() {
        return new Map(this.filtered.map((p, i) => [p.id, i]));
    },

    visible(id) {
        return this.orderMap.has(id);
    },

    position(id) {
        return this.orderMap.get(id) ?? 0;
    },

    setCategory(c) {
        this.category = c;
        document.getElementById('produk')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },

    reset() {
        this.category = 'semua';
        this.brand = '';
        this.q = '';
        this.sort = 'rekomendasi';
    },

    open(p) {
        this.selected = p;
        this.qty = 1;
    },

    close() {
        this.selected = null;
    },

    async add(p, qty = 1) {
        this.busy = p.id;
        const ok = await Alpine.store('cart').add(p.id, qty);
        this.busy = null;
        if (ok && this.selected) this.close();
    },
}));

/* ---------- Grafik penjualan di dashboard admin ---------- */
Alpine.data('salesChart', (chart) => ({
    instance: null,
    init() {
        const css = getComputedStyle(document.documentElement);
        const primary = css.getPropertyValue('--primary').trim();
        const accent = css.getPropertyValue('--accent').trim();
        const muted = css.getPropertyValue('--muted-foreground').trim();

        this.instance = new Chart(this.$refs.canvas, {
            data: {
                labels: chart.labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Pendapatan (Rp)',
                        data: chart.revenue,
                        backgroundColor: primary,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        type: 'line',
                        label: 'Jumlah pesanan',
                        data: chart.orders,
                        borderColor: accent,
                        backgroundColor: accent,
                        borderWidth: 3,
                        tension: 0.35,
                        pointRadius: 3,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { labels: { color: muted, usePointStyle: true } } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: muted } },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: muted,
                            callback: (v) => (v >= 1e6 ? v / 1e6 + ' jt' : v),
                        },
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: muted, precision: 0 },
                    },
                },
            },
        });
    },
    destroy() {
        this.instance?.destroy();
    },
}));

Alpine.start();
