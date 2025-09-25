<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LibraryBig, Library, Trash, List, BookOpen, FolderCog, Folder, LayoutGrid, Users, UserRoundCog, ShieldCheck, FileText } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
];

if (page.props.auth?.permissions?.includes('users.show')) {
  mainNavItems.push({
    title: 'Users',
    href: '/users',
    icon: Users,
  });
}
if (page.props.auth?.permissions?.includes('roles.view')) {
  mainNavItems.push({
    title: 'Roles',
    href: '/roles',
    icon: UserRoundCog,
  });
}
if (page.props.auth?.permissions?.includes('permissions.view')){
    mainNavItems.push({
        title: 'Permissions',
        href: '/permissions',
        icon: ShieldCheck,
    });
}
if (page.props.auth?.permissions?.includes('articles.view')) {
  mainNavItems.push({
    title: 'Articles',
    href: '/articles',
    icon: FileText,
  });
}
if(page.props.auth?.permissions?.includes('articles.view')){
  mainNavItems.push({
    title: 'Approved Article',
    href: '/articles/approved',
    icon: List,
  });
}
if (page.props.auth?.permissions?.includes('articles.trashed')) {
  mainNavItems.push({
    title: 'Trashed',
    href: '/articles/trashed',
    icon: Trash,
  });
}
if (page.props.auth?.permissions?.includes('manage.categories')) {
  mainNavItems.push({
    title: 'Category',
    href: '/categories/create',
    icon: LibraryBig,
  });
}
if (page.props.auth?.permissions?.includes('manage.tags')) {
  mainNavItems.push({
    title: 'Tag',
    href: '/tags/create',
    icon: Library,
  });
}
if (page.props.auth?.permissions?.includes('articles.approve')) {
    mainNavItems.push({
        title: 'Moderation',
        href: '/articles/moderation',
        icon: FolderCog,
    });
}



const footerNavItems: NavItem[] = [
  {
    title: 'Github Repo',
    href: 'https://github.com/laravel/vue-starter-kit',
    icon: Folder,
  },
  {
    title: 'Documentation',
    href: 'https://laravel.com/docs/starter-kits#vue',
    icon: BookOpen,
  },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="route('home')">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <!-- ini yang penting -->
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <NavFooter :items="footerNavItems" />
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
