<footer>
    <p><small>&copy; <?= date('Y') ?> Інформаційна система куратора</small></p>
</footer>

<div class="modal-overlay" id="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-wrap">
            <div class="modal-icon warn" id="modal-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div class="modal-title"   id="modal-title">Підтвердження</div>
        <div class="modal-message" id="modal-message"></div>
        <div class="modal-actions">
            <button class="modal-btn modal-btn-cancel"  id="modal-cancel">Скасувати</button>
            <button class="modal-btn modal-btn-confirm" id="modal-confirm">Підтвердити</button>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(20,18,16,.45); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; padding: 1rem;
    opacity: 0; pointer-events: none; transition: opacity .2s ease;
}
.modal-overlay.active { opacity: 1; pointer-events: all; }

.modal-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.1);
    padding: 2rem 2rem 1.75rem; max-width: 380px; width: 100%; text-align: center;
    transform: translateY(12px) scale(.97); opacity: 0;
    transition: transform .22s cubic-bezier(.34,1.56,.64,1), opacity .2s ease;
}
.modal-overlay.active .modal-card { transform: translateY(0) scale(1); opacity: 1; }

.modal-icon-wrap { margin-bottom: 1rem; }
.modal-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 52px; height: 52px; border-radius: 50%; font-size: 1.3rem;
}
.modal-icon.warn    { background: #fef3e2; color: #c07010; }
.modal-icon.danger  { background: #fdf0f0; color: #b83232; }
.modal-icon.success { background: #edf8f2; color: #1e7a4a; }

.modal-title {
    font-family: 'DM Sans', sans-serif; font-size: 1.05rem; font-weight: 600;
    color: #181715; margin-bottom: .5rem; letter-spacing: -.01em;
}
.modal-message {
    font-family: 'DM Sans', sans-serif; font-size: .875rem;
    color: #5a5750; line-height: 1.55; margin-bottom: 1.5rem;
}
.modal-actions { display: flex; gap: .65rem; justify-content: center; }

.modal-btn {
    font-family: 'DM Sans', sans-serif; font-size: .875rem; font-weight: 500;
    padding: .52rem 1.4rem; border-radius: 7px; border: 1px solid transparent;
    cursor: pointer; transition: all .15s ease; flex: 1; text-align: center;
    display: flex; align-items: center; justify-content: center;
}
.modal-btn-cancel  { background: #f7f5f2; color: #5a5750; border-color: #e2ddd7; }
.modal-btn-cancel:hover { background: #ede9e3; border-color: #c4bfb7; color: #181715; }
.modal-btn-confirm { background: #b83232; color: #fff; border-color: #b83232; }
.modal-btn-confirm:hover { background: #9a2828; border-color: #9a2828; }
.modal-btn-confirm.is-save { background: #2e6b57; border-color: #2e6b57; }
.modal-btn-confirm.is-save:hover { background: #1f4e3d; border-color: #1f4e3d; }
</style>

<script>
(function () {
    const overlay  = document.getElementById('modal-overlay');
    const titleEl  = document.getElementById('modal-title');
    const msgEl    = document.getElementById('modal-message');
    const iconEl   = document.getElementById('modal-icon');
    const btnOk    = document.getElementById('modal-confirm');
    const btnCancel = document.getElementById('modal-cancel');

    const ICONS = { warn: 'fa-triangle-exclamation', danger: 'fa-trash', success: 'fa-floppy-disk' };
    let resolve = null;
    let isOpen  = false;

    window.showModal = ({ title = 'Підтвердження', message = '', type = 'warn',
                          confirmText = 'Підтвердити', cancelText = 'Скасувати' }) => {
        titleEl.textContent = title;
        msgEl.textContent   = message;
        btnOk.textContent   = confirmText;
        btnCancel.textContent = cancelText;
        iconEl.className    = 'modal-icon ' + type;
        iconEl.innerHTML    = `<i class="fa-solid ${ICONS[type] || ICONS.warn}"></i>`;
        btnOk.className     = 'modal-btn modal-btn-confirm' + (type === 'success' ? ' is-save' : '');
        overlay.classList.add('active');
        btnCancel.focus();
        return new Promise(res => { resolve = res; });
    };

    function close(result) {
        overlay.classList.remove('active');
        if (resolve) { resolve(result); resolve = null; }
    }

    btnOk.addEventListener('click',    () => close(true));
    btnCancel.addEventListener('click', () => close(false));
    overlay.addEventListener('click',   e => e.target === overlay && close(false));
    document.addEventListener('keydown', e => {
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'Escape') close(false);
        if (e.key === 'Enter')  { e.preventDefault(); close(true); }
    });

    const confirmed = new WeakSet();

    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (confirmed.has(form) || isOpen) return;

        const isDelete = form.action.includes('delete_student') || form.action.includes('delete_parent');
        const isSave   = form.action.includes('update_student') || form.action.includes('update_parent') || form.action.includes('insert_student');
        if (!isDelete && !isSave) return;

        e.preventDefault();
        e.stopImmediatePropagation();
        isOpen = true;

        let ok;
        if (isDelete) {
            const nameEl = form.closest('tr')?.querySelector('td strong') ??
                           form.closest('fieldset')?.querySelector('input[name="full_name"]');
            const name   = nameEl ? (nameEl.textContent || nameEl.value || 'цього запису') : 'цього запису';
            ok = await window.showModal({
                title: 'Видалити запис?',
                message: `Ви впевнені, що хочете видалити «${name}»? Всі пов'язані дані будуть втрачені назавжди.`,
                type: 'danger', confirmText: 'Так, видалити',
            });
        } else {
            ok = await window.showModal({
                title: 'Зберегти зміни?',
                message: 'Підтвердіть збереження змін до картки студента.',
                type: 'success', confirmText: 'Зберегти',
            });
        }

        isOpen = false;
        if (ok) { confirmed.add(form); form.submit(); }
    });
}());
</script>

</body>
</html>
