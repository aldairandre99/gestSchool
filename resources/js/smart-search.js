// Smart search: global command palette (Cmd/Ctrl+K).
// Exposed as Alpine.data('smartSearch'). The view uses <div x-data="smartSearch()">.

document.addEventListener('alpine:init', () => {
    window.Alpine.data('smartSearch', () => ({
        open: false,
        query: '',
        groups: [],
        loading: false,
        activeIndex: 0,
        debounceTimer: null,
        controller: null,

        init() {
            this.bindGlobalShortcut();
        },

        bindGlobalShortcut() {
            document.addEventListener('keydown', (e) => {
                const isOpen = (e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k';
                if (!isOpen) return;
                const t = e.target;
                const editable = t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.isContentEditable);
                if (editable && !this.open) return;
                e.preventDefault();
                this.openPalette();
            });
        },

        openPalette() {
            this.open = true;
            this.$nextTick(() => {
                const input = this.$refs.input;
                if (input) input.focus();
            });
        },

        closePalette() {
            this.open = false;
            this.query = '';
            this.groups = [];
            this.activeIndex = 0;
            if (this.controller) this.controller.abort();
        },

        get flatResults() {
            const out = [];
            this.groups.forEach((g) => {
                g.results.forEach((r) => out.push(r));
            });
            return out;
        },

        get totalCount() {
            return this.flatResults.length;
        },

        flatIndexFor(groupIdx, resultIdx) {
            let n = 0;
            for (let i = 0; i < groupIdx; i++) n += this.groups[i].results.length;
            return n + resultIdx;
        },

        onInput() {
            clearTimeout(this.debounceTimer);
            const q = this.query.trim();
            if (q.length < 2) {
                this.groups = [];
                this.activeIndex = 0;
                return;
            }
            this.debounceTimer = setTimeout(() => this.fetchResults(q), 180);
        },

        async fetchResults(q) {
            if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            this.loading = true;
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    signal: this.controller.signal,
                });
                if (!res.ok) throw new Error('search failed');
                const data = await res.json();
                this.groups = data.groups || [];
                this.activeIndex = 0;
            } catch (e) {
                if (e.name !== 'AbortError') {
                    this.groups = [];
                }
            } finally {
                this.loading = false;
            }
        },

        moveActive(delta) {
            const max = this.totalCount - 1;
            if (max < 0) return;
            let next = this.activeIndex + delta;
            if (next < 0) next = max;
            if (next > max) next = 0;
            this.activeIndex = next;
            this.$nextTick(() => {
                const el = document.querySelector(`[data-search-idx="${next}"]`);
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        },

        commit() {
            const r = this.flatResults[this.activeIndex];
            if (r && r.url) window.location.href = r.url;
        },

        iconFor(name) {
            const el = document.querySelector(`#search-icon-bank [data-icon="${name}"]`);
            return el ? el.innerHTML : '';
        },

        onKey(e) {
            switch (e.key) {
                case 'Escape':
                    this.closePalette();
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    this.moveActive(1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.moveActive(-1);
                    break;
                case 'Enter':
                    e.preventDefault();
                    this.commit();
                    break;
            }
        },
    }));
});
