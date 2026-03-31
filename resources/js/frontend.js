import { setupCodeCopy } from './modules/code-copy';
import { registerServiceWorker } from './modules/pwa';
import { setupScrollToTop } from './modules/scroll-to-top';

setupCodeCopy();
registerServiceWorker();

const initFrontendUi = () => {
    setupScrollToTop();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFrontendUi, { once: true });
} else {
    initFrontendUi();
}

document.addEventListener('livewire:navigated', initFrontendUi);
