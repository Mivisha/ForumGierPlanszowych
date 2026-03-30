@once('boardgames-genre-picker')
    <script>
        (() => {
            const initSelectors = () => {
                document.querySelectorAll('[data-genre-select]').forEach((container) => {
                    if (container.dataset.genreReady === 'true') {
                        return;
                    }
                    container.dataset.genreReady = 'true';

                    const toggle = container.querySelector('[data-genre-toggle]');
                    const panel = container.querySelector('[data-genre-panel]');
                    const list = container.querySelector('[data-selected-list]');
                    const empty = container.querySelector('[data-selected-empty]');
                    const count = container.querySelector('[data-selected-count]');
                    const checkboxes = container.querySelectorAll('.genre-dropdown__checkbox');

                    if (!toggle || !panel || !list || !empty || !count) {
                        return;
                    }

                    let outsideBound = false;
                    const handleOutsideClick = (event) => {
                        if (!container.contains(event.target)) {
                            closePanel();
                        }
                    };

                    const openPanel = () => {
                        panel.hidden = false;
                        toggle.setAttribute('aria-expanded', 'true');
                        container.classList.add('is-open');
                        if (!outsideBound) {
                            document.addEventListener('click', handleOutsideClick);
                            outsideBound = true;
                        }
                    };

                    const closePanel = () => {
                        panel.hidden = true;
                        toggle.setAttribute('aria-expanded', 'false');
                        container.classList.remove('is-open');
                        if (outsideBound) {
                            document.removeEventListener('click', handleOutsideClick);
                            outsideBound = false;
                        }
                    };

                    toggle.addEventListener('click', (event) => {
                        event.preventDefault();
                        panel.hidden ? openPanel() : closePanel();
                    });

                    const rebuild = () => {
                        const selected = Array.from(checkboxes).filter((cb) => cb.checked);
                        list.innerHTML = '';

                        selected.forEach((cb) => {
                            const chip = document.createElement('button');
                            chip.type = 'button';
                            chip.className = 'genre-chip';
                            chip.setAttribute('aria-label', 'Usuń gatunek ' + (cb.dataset.genreLabel || cb.value));
                            chip.innerHTML =
                                '<span>' + (cb.dataset.genreLabel || cb.value) + '</span>' +
                                '<span class="genre-chip__remove" aria-hidden="true">&times;</span>';
                            chip.addEventListener('click', () => {
                                cb.checked = false;
                                cb.dispatchEvent(new Event('change', { bubbles: true }));
                            });
                            list.appendChild(chip);
                        });

                        count.textContent = selected.length;
                        empty.hidden = selected.length > 0;
                        list.hidden = selected.length === 0;
                    };

                    checkboxes.forEach((cb) => cb.addEventListener('change', rebuild));
                    rebuild();
                });
            };

            const boot = () => initSelectors();

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot, { once: true });
            } else {
                boot();
            }

            document.addEventListener('livewire:navigated', boot);
        })();
    </script>
@endonce