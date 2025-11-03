import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

// Tipe data kanggo link pagination Laravel
export interface LaravelLink {
    url: string | null;
    label: string;
    active: boolean;
}

// Tipe data kanggo meta pagination Laravel
export interface LaravelPaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    links: LaravelLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
}

export interface PaginatedData<T> {
    data: T[];
    links: LaravelLink[]; // Kadang link aya di root, kadang di meta
    meta: LaravelPaginationMeta;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    username: string;
    name: string;
    email: string;
    has_password: boolean;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
