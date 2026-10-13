import { Link } from '@inertiajs/react';
import {
    BarChart3,
    BookOpen,
    CreditCard,
    Download,
    LayoutGrid,
    Settings,
    ShoppingBag,
    Sparkles,
    Users,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/account/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'My Learning',
        href: '/account/weekly-learning',
        icon: BookOpen,
    },
    {
        title: 'Downloads',
        href: '/account/library',
        icon: Download,
    },
    {
        title: 'Membership',
        href: '/account/membership',
        icon: CreditCard,
    },
    {
        title: 'Orders',
        href: '/account/orders',
        icon: ShoppingBag,
    },
    {
        title: 'Progress',
        href: '/account/progress',
        icon: BarChart3,
    },
    {
        title: 'Skill Checks',
        href: '/account/assessments',
        icon: Sparkles,
    },
    {
        title: 'Recommendations',
        href: '/account/recommendations',
        icon: BookOpen,
    },
    {
        title: 'Profiles',
        href: '/account/profiles',
        icon: Users,
    },
    {
        title: 'Settings',
        href: '/account/settings',
        icon: Settings,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/account/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
