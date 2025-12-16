
document.addEventListener('DOMContentLoaded', () => {
    const isInlineCode = (element) => {
        if (!(element instanceof HTMLElement)) {
            return true;
        }

        const isCodeTag = element.tagName === 'CODE';
        const isInsidePre = !!element.closest('pre');
        const hasMultilineContent = element.innerText.includes('\n');

        if (!isCodeTag) {
            return false;
        }

        if (isInsidePre) {
            return false;
        }

        const displayStyle = window.getComputedStyle(element).display;

        return displayStyle === 'inline' && !hasMultilineContent;
    };

    const getCopyTargets = () => {
        const blocks = document.querySelectorAll('.ck-content pre, .ck-content code');
        const uniqueBlocks = new Set();

        blocks.forEach((block) => {
            const container = block.closest('pre') ?? block;
            uniqueBlocks.add(container);
        });

        return Array.from(uniqueBlocks).filter((block) => !isInlineCode(block));
    };

    const initCodeCopy = () => {
        const codeBlocks = getCopyTargets();

        codeBlocks.forEach((block) => {
            if (block.querySelector('.code-copy-btn')) {
                return;
            }

            block.classList.add('code-block-container');

            const copyButton = document.createElement('button');
            copyButton.type = 'button';
            copyButton.className = 'code-copy-btn';
            copyButton.innerHTML = '<i class="fa-regular fa-copy"></i><span class="sr-only">কপি করুন</span>';

            copyButton.addEventListener('click', async () => {
                const codeElement = block.querySelector('code');
                const code = codeElement?.innerText ?? block.innerText;

                try {
                    await navigator.clipboard.writeText(code.trim());
                    copyButton.classList.add('copied');
                    copyButton.innerHTML = '<i class="fa-solid fa-check"></i><span class="sr-only">কপি সম্পন্ন</span>';
                } catch (error) {
                    console.error('কপি করতে সমস্যা হয়েছে', error);
                    copyButton.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i><span class="sr-only">কপি ব্যর্থ</span>';
                }

                setTimeout(() => {
                    copyButton.classList.remove('copied');
                    copyButton.innerHTML = '<i class="fa-regular fa-copy"></i><span class="sr-only">কপি করুন</span>';
                }, 1500);
            });

            block.appendChild(copyButton);
        });
    };

    const observeCodeBlocks = () => {
        const observer = new MutationObserver((mutations) => {
            let shouldInit = false;

            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (!(node instanceof HTMLElement)) {
                        return;
                    }

                    if (
                        node.matches?.('.ck-content pre, .ck-content code') ||
                        node.querySelector?.('.ck-content pre, .ck-content code')
                    ) {
                        shouldInit = true;
                    }
                });
            });

            if (shouldInit) {
                initCodeCopy();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    };

    initCodeCopy();
    observeCodeBlocks();
    document.addEventListener('livewire:navigated', initCodeCopy);

    document.addEventListener('livewire:init', () => {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('message.processed', initCodeCopy);
        }
    });
});
