
document.addEventListener('DOMContentLoaded', () => {
    const initCodeCopy = () => {
        const codeBlocks = document.querySelectorAll('.ck-content pre');

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
                const code = block.querySelector('code')?.innerText ?? block.innerText;

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

    initCodeCopy();
    document.addEventListener('livewire:navigated', initCodeCopy);

    document.addEventListener('livewire:init', () => {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('message.processed', initCodeCopy);
        }
    });
});
