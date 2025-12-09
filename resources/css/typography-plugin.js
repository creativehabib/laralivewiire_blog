import plugin from "tailwindcss/plugin";

export default plugin(function ({ addComponents, theme }) {
    const slate = theme('colors.slate');
    const primary = theme('colors.primary.DEFAULT') || '#0d6efd';

    addComponents({
        '.prose-article': {
            color: (slate && slate[800]) || '#1f2937',
            lineHeight: '1.75',
            maxWidth: '100%',
            fontSize: theme('fontSize.base')[0],
        },
        '.dark .prose-article': {
            color: (slate && slate[100]) || '#e2e8f0',
        },
        '.prose-article h1, .prose-article h2, .prose-article h3, .prose-article h4, .prose-article h5, .prose-article h6': {
            fontWeight: theme('fontWeight.semibold'),
            lineHeight: '1.25',
            letterSpacing: '-0.01em',
            marginBottom: '0.75em',
            color: 'inherit',
        },
        '.prose-article h1': { fontSize: theme('fontSize.3xl')[0] },
        '.prose-article h2': { fontSize: theme('fontSize.2xl')[0] },
        '.prose-article h3': { fontSize: theme('fontSize.xl')[0] },
        '.prose-article h4': { fontSize: theme('fontSize.lg')[0] },
        '.prose-article p, .prose-article li': {
            marginBottom: '1em',
        },
        '.prose-article a': {
            color: primary,
            fontWeight: theme('fontWeight.medium'),
            textDecoration: 'underline',
            textDecorationColor: 'color-mix(in srgb, currentColor 70%, transparent)',
            textUnderlineOffset: '0.12em',
        },
        '.prose-article a:hover': {
            color: theme('colors.primary.dark') || primary,
            textDecorationColor: 'currentColor',
        },
        '.prose-article ul': { listStyleType: 'disc', paddingLeft: '1.25rem' },
        '.prose-article ol': { listStyleType: 'decimal', paddingLeft: '1.25rem' },
        '.prose-article blockquote': {
            borderLeftWidth: '4px',
            borderLeftColor: (primary ?? '#0d6efd') + '33',
            paddingLeft: '1rem',
            fontStyle: 'italic',
            color: 'inherit',
        },
        '.prose-article code': {
            backgroundColor: (slate && slate[100]) || '#f1f5f9',
            color: (slate && slate[800]) || '#1f2937',
            padding: '0.15em 0.4em',
            borderRadius: theme('borderRadius.md'),
            fontSize: '0.9em',
            fontFamily: theme('fontFamily.mono').join(', '),
        },
        '.dark .prose-article code': {
            backgroundColor: (slate && slate[800]) || '#1e293b',
            color: (slate && slate[100]) || '#e2e8f0',
        },
        '.prose-article pre': {
            backgroundColor: (slate && slate[900]) || '#0f172a',
            color: (slate && slate[100]) || '#e2e8f0',
            padding: '1rem',
            borderRadius: theme('borderRadius.xl'),
            overflowX: 'auto',
            borderWidth: '1px',
            borderColor: (slate && slate[800]) || '#1e293b',
            boxShadow: theme('boxShadow.sm'),
        },
        '.prose-article pre code': {
            backgroundColor: 'transparent',
            color: 'inherit',
            padding: '0',
            borderRadius: '0',
        },
        '.prose-article table': {
            width: '100%',
            borderCollapse: 'collapse',
            fontSize: '0.95em',
            marginBottom: '1.25em',
        },
        '.prose-article th, .prose-article td': {
            borderWidth: '1px',
            borderColor: (slate && slate[200]) || '#e2e8f0',
            padding: '0.75rem',
            textAlign: 'left',
        },
        '.dark .prose-article th, .dark .prose-article td': {
            borderColor: (slate && slate[700]) || '#334155',
        },
        '.prose-article thead th': {
            backgroundColor: (slate && slate[50]) || '#f8fafc',
            fontWeight: theme('fontWeight.semibold'),
        },
        '.dark .prose-article thead th': {
            backgroundColor: (slate && slate[800]) || '#1e293b',
        },
        '.prose-article tbody tr:nth-child(odd)': {
            backgroundColor: (slate && slate[50]) || '#f8fafc',
        },
        '.dark .prose-article tbody tr:nth-child(odd)': {
            backgroundColor: 'color-mix(in srgb, #0f172a 90%, transparent)',
        },
        '.prose-article img, .prose-article video': {
            borderRadius: theme('borderRadius.lg'),
            margin: '1rem 0',
        },
    });
});
