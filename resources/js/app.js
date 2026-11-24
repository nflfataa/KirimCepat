const numberFormatter = new Intl.NumberFormat('id-ID');
const decimalFormatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
const packageFields = ['zone_id', 'actual_weight', 'length', 'width', 'height'];

const formatMoney = (value) => 'Rp' + numberFormatter.format(Number(value) || 0);
const formatWeight = (value) => decimalFormatter.format(Number(value) || 0) + ' kg';

function firstMessage(body) {
    if (body && body.errors) {
        const first = Object.values(body.errors)[0];

        if (Array.isArray(first) && first.length > 0) {
            return first[0];
        }
    }

    return body?.message || 'Ongkir tidak bisa dihitung. Periksa kembali data paket.';
}

function readPayload(form) {
    const data = new FormData(form);
    const payload = {};

    for (const field of packageFields) {
        const value = String(data.get(field) ?? '').trim();

        if (value === '') {
            return null;
        }

        payload[field] = value;
    }

    return payload;
}

function emptyState(target, message) {
    const paragraph = document.createElement('p');
    paragraph.className = 'calc-empty';
    paragraph.textContent = message;
    target.replaceChildren(paragraph);
}

function showError(target, message) {
    const paragraph = document.createElement('p');
    paragraph.className = 'calc-empty is-error';
    paragraph.textContent = message;
    target.replaceChildren(paragraph);
}

function addRow(body, label, value) {
    const row = document.createElement('tr');
    const heading = document.createElement('th');
    const cell = document.createElement('td');

    heading.scope = 'row';
    heading.textContent = label;
    cell.textContent = value;
    row.append(heading, cell);
    body.append(row);
}

function renderBreakdown(target, data, form) {
    const table = document.createElement('table');
    table.className = 'breakdown';
    const body = document.createElement('tbody');

    addRow(body, 'Berat aktual', formatWeight(data.actual_weight_kg));
    addRow(body, 'Berat volumetrik', formatWeight(data.volumetric_weight_kg));
    addRow(body, 'Berat dikenakan', formatWeight(data.chargeable_weight_kg));
    addRow(body, 'Tarif zona', formatMoney(data.rate_per_kg) + '/kg');
    addRow(body, 'Total ongkir', formatMoney(data.total_cost));
    table.append(body);

    const note = document.createElement('p');
    note.className = 'calc-foot';
    note.textContent = `${data.zone.name} · tarif ${formatMoney(data.zone.rate_per_kg)}/kg`;

    target.replaceChildren(table, note);

    if (form.dataset.rateCta === 'true') {
        const payload = readPayload(form);
        const params = new URLSearchParams(payload ?? {});
        const link = document.createElement('a');

        link.className = 'btn btn-primary';
        link.href = (form.dataset.createUrl || '/kirim') + '?' + params.toString();
        link.innerHTML = `
            <span>Buat pengiriman dengan hitungan ini</span>
            <svg class="w-4 h-4 ml-1.5 inline-block shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        `;
        target.append(link);
    }
}

async function requestRate(payload, endpoint, signal) {
    const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
        signal,
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(firstMessage(data));
    }

    return data;
}

function targetFor(form) {
    return form.dataset.rateTarget ? document.querySelector(form.dataset.rateTarget) : null;
}

function endpointFor(form) {
    return form.dataset.endpoint || '/api/ongkir';
}

function bindCalculator(form) {
    const target = targetFor(form);

    if (!target) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const payload = readPayload(form);

        if (!payload) {
            emptyState(target, target.dataset.emptyMessage || 'Lengkapi zona, berat, dan dimensi.');
            return;
        }

        const button = form.querySelector('[type="submit"]');
        const originalLabel = button ? button.textContent : '';

        if (button) {
            button.disabled = true;
            button.textContent = 'Menghitung…';
        }

        emptyState(target, 'Menghitung ongkir…');

        try {
            renderBreakdown(target, await requestRate(payload, endpointFor(form)), form);
            if (window.innerWidth < 1024) {
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        } catch (error) {
            showError(target, error.message);
        } finally {
            if (button) {
                button.disabled = false;
                button.textContent = originalLabel;
            }
        }
    });
}

function bindLiveSummary(form) {
    const target = targetFor(form);

    if (!target) {
        return;
    }

    let timer;
    let controller;

    const run = async () => {
        const payload = readPayload(form);

        if (!payload) {
            emptyState(
                target,
                target.dataset.emptyMessage ||
                    'Lengkapi zona, berat, dan dimensi untuk melihat rincian ongkir.',
            );
            return;
        }

        controller?.abort();
        controller = new AbortController();
        emptyState(target, 'Menghitung ongkir…');

        try {
            renderBreakdown(
                target,
                await requestRate(payload, endpointFor(form), controller.signal),
                form,
            );
        } catch (error) {
            if (error.name !== 'AbortError') {
                showError(target, error.message);
            }
        }
    };

    const schedule = (event) => {
        if (event.target.name && !packageFields.includes(event.target.name)) {
            return;
        }

        clearTimeout(timer);
        timer = setTimeout(run, 450);
    };

    form.addEventListener('input', schedule);
    form.addEventListener('change', schedule);

    if (readPayload(form)) {
        run();
    }
}

async function copyText(value) {
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(value);

            return;
        } catch {
            // Clipboard permission can be denied; the selection fallback still works.
        }
    }

    const area = document.createElement('textarea');
    area.value = value;
    area.setAttribute('readonly', '');
    area.style.position = 'fixed';
    area.style.opacity = '0';
    document.body.append(area);
    area.select();

    const copied = document.execCommand('copy');
    area.remove();

    if (!copied) {
        throw new Error('Salin manual diperlukan.');
    }
}

function bindCopyButtons(announcer) {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-copy]');

        if (!button) {
            return;
        }

        const value = button.getAttribute('data-copy');

        if (!value) {
            return;
        }

        try {
            await copyText(value);

            if (announcer) {
                announcer.textContent = 'Nomor resi disalin.';
            }

            const original = button.textContent;
            button.textContent = 'Tersalin';
            setTimeout(() => {
                button.textContent = original;
            }, 2000);
        } catch {
            if (announcer) {
                announcer.textContent = 'Nomor resi tidak bisa disalin otomatis. Pilih teks resi secara manual.';
            }
        }
    });
}

function bindNavigation() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.getElementById('primary-nav');

    if (!toggle || !nav) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        nav.dataset.open = String(open);
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    nav.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
            toggle.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (
            toggle.getAttribute('aria-expanded') === 'true' &&
            !nav.contains(event.target) &&
            !toggle.contains(event.target)
        ) {
            setOpen(false);
        }
    });

    window.matchMedia('(min-width: 761px)').addEventListener('change', (event) => {
        if (event.matches) {
            setOpen(false);
        }
    });
}

function bindSmoothScroll() {
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href^="#"]');
        if (!link) {
            return;
        }

        const href = link.getAttribute('href');
        if (!href || href === '#') {
            return;
        }

        const target = document.querySelector(href);
        if (target) {
            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            history.pushState(null, '', href);
        }
    });
}

document.querySelectorAll('[data-rate-form]').forEach(bindCalculator);
document.querySelectorAll('[data-rate-watch]').forEach(bindLiveSummary);
bindCopyButtons(document.querySelector('[data-announcer]'));
bindNavigation();
bindSmoothScroll();
