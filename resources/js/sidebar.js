// Sidebar interactions: collapse toggle, per-group collapse, persistence, keyboard shortcut.

const STORAGE_COLLAPSED = 'sb:collapsed';
const STORAGE_GROUPS = 'sb:groups';

function readJSON(key, fallback) {
    try {
        const raw = localStorage.getItem(key);
        return raw == null ? fallback : JSON.parse(raw);
    } catch (e) {
        return fallback;
    }
}

function writeJSON(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {}
}

function setCollapsed(value) {
    const html = document.documentElement;
    html.classList.toggle('sidebar-collapsed', value);
    writeJSON(STORAGE_COLLAPSED, value);
}

function initGroups() {
    const state = readJSON(STORAGE_GROUPS, {});
    document.querySelectorAll('[data-sb-group]').forEach((group) => {
        const key = group.dataset.sbGroup;
        if (state[key] === false) {
            group.classList.add('is-closed');
        }
    });
}

function toggleGroup(key) {
    const group = document.querySelector(`[data-sb-group="${CSS.escape(key)}"]`);
    if (!group) return;
    const closed = group.classList.toggle('is-closed');
    const state = readJSON(STORAGE_GROUPS, {});
    state[key] = !closed;
    writeJSON(STORAGE_GROUPS, state);
}

function bind() {
    document.querySelectorAll('[data-sb-toggle-collapse]').forEach((btn) => {
        btn.addEventListener('click', () => {
            setCollapsed(!document.documentElement.classList.contains('sidebar-collapsed'));
        });
    });

    document.querySelectorAll('[data-sb-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => toggleGroup(btn.dataset.sbToggle));
    });

    document.addEventListener('keydown', (e) => {
        const isToggle = (e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b';
        if (!isToggle) return;
        const target = e.target;
        const isEditable = target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.isContentEditable);
        if (isEditable) return;
        e.preventDefault();
        setCollapsed(!document.documentElement.classList.contains('sidebar-collapsed'));
    });
}

function init() {
    initGroups();
    bind();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
