import { createInertiaApp } from '@inertiajs/react';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AppLayout from '@/layouts/app-layout';
import AuthLayout from '@/layouts/auth-layout';
import SettingsLayout from '@/layouts/settings/layout';

const appName = import.meta.env.VITE_APP_NAME || 'KiddoZen';

void createInertiaApp({
    title: (title) => {
        if (!title) {
            return appName;
        }
        if (title.includes(appName) || title.includes('KiddoZen')) {
            return title;
        }
        return `${title} - ${appName}`;
    },
    layout: (name) => {
        switch (true) {
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                // All other pages (public pages like home, learn, free, search, shop,
                // membership, parents, articles, cart, checkout, assessments, as well
                // as account/orders pages) manage and render their own layout
                // (PublicLayout or AppLayout) directly.
                return null;
        }
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
