import './bootstrap';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Swal = Swal;

window.SigmaFormFeedback = {
    showMessage(title, text, icon = 'warning') {
        return Swal.fire({ icon, title, text, confirmButtonText: 'Entendido', confirmButtonColor: '#2563eb' });
    },
    showFirstInvalid(form, message = null) {
        const field = form.querySelector(':invalid');
        const label = field?.labels?.[0]?.textContent?.replace('*', '').trim()
            || field?.getAttribute('aria-label')
            || field?.name?.replace(/[_\[\]]/g, ' ')
            || 'un campo obligatorio';
        const text = message || (field?.validationMessage ? `${label}: ${field.validationMessage}` : `Revisa ${label} antes de continuar.`);
        return this.showMessage('Revisa la información', text).then(() => {
            if (field) {
                field.focus({ preventScroll: true });
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    },
};

const hasRequiredData = (form) => {
    const names = (form.dataset.swalRequiredHidden || '').split(',').map((name) => name.trim()).filter(Boolean);
    for (const name of names) {
        const fields = [...form.querySelectorAll(`[name="${CSS.escape(name)}"]`)];
        if (fields.some((field) => String(field.value || '').trim() !== '')) continue;
        const labels = {
            asset_id: 'Selecciona un activo antes de continuar.',
            type: 'Selecciona el tipo de servicio.',
            technician_id: 'Selecciona el técnico responsable.',
            'asset_ids[]': 'Selecciona al menos un equipo para programar.',
        };
        window.SigmaFormFeedback.showMessage('Información incompleta', labels[name] || 'Completa los datos obligatorios.');
        return false;
    }
    return true;
};

for (const form of document.querySelectorAll('[data-sigma-create-form]:not([data-sigma-create-form="manual"])')) {
    form.noValidate = true;
    form.addEventListener('submit', (event) => {
        if (form.dataset.swalConfirmed === 'true') {
            delete form.dataset.swalConfirmed;
            return;
        }
        event.preventDefault();
        event.stopImmediatePropagation();
        if (!form.checkValidity()) {
            window.SigmaFormFeedback.showFirstInvalid(form);
            return;
        }
        if (!hasRequiredData(form)) return;
        Swal.fire({
            icon: 'question',
            title: form.dataset.swalTitle || '¿Confirmas la creación?',
            text: form.dataset.swalText || 'Verifica la información antes de guardar este registro.',
            showCancelButton: true,
            confirmButtonText: form.dataset.swalConfirmText || 'Sí, guardar',
            cancelButtonText: 'Revisar',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (!result.isConfirmed) return;
            form.dataset.swalConfirmed = 'true';
            form.requestSubmit(event.submitter || undefined);
        });
    }, true);
}

