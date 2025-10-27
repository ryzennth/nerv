<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Eye, EyeOff } from 'lucide-vue-next';
import Password from 'primevue/password'
import InputText from 'primevue/inputtext';
import Swal from 'sweetalert2';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    login: '',
    password: '',
    remember: false,
});


const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
const page = usePage();
const session = page.props?.session?.message;
if (session) {
   Swal.fire({
  title: 'Harap Login Kembali',
  icon: 'success',
  text: 'Akun anda sudah terdaftar dan terverfikasi',
  allowOutsideClick: false,
  allowEscapeKey: false,
  showCancelButton: false,
  confirmButtonText: 'Baiklah',
}).then((result) => {
  if (result.isConfirmed) {
    // Arahkan ke halaman pengecekan status verifikasi atau reload
    window.location.href='/clear-message';
  }
});
}

const showPassword = ref(false);
</script>


<template>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-background dark:bg-background text-foreground dark:text-foreground">
        <div class="flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12"> <AuthBase title="Log in to your account" description="Enter your username and password below to log in">
                <Head title="Log in" />

                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="login">Username atau Email</Label>
                            <input
                                id="login"
                                type="text"
                                required
                                autofocus
                                :tabindex="1"
                                v-model="form.login"
                                class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                                placeholder="Username / Email"
                            />
                            <InputError :message="form.errors.login" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password">Password</Label>
                                <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm">Forgot password?</TextLink>
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'" required
                                    v-model="form.password"
                                    class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                                    placeholder="Password"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-muted dark:text-muted hover:text-foreground dark:hover:text-foreground focus:outline-none"
                                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                >
                                    <EyeOff v-if="showPassword" class="h-5 w-5" /> <Eye v-else class="h-5 w-5" />               </button>
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="remember" v-model:checked="form.remember" :tabindex="3" />
                            <Label for="remember">Remember me</Label>
                        </div>

                        <Button type="submit" class="mt-4 w-full bg-(--brand) hover:opacity-80 text-primary-foreground font-medium py-2 rounded-lg"
                            :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="inline-block mr-2 h-4 w-4 animate-spin" />
                            Log in
                        </Button>

                        <div class="relative my-6">
                             <div class="absolute inset-0 flex items-center">
                                 <span class="w-full border-t border-border dark:border-border"></span>
                             </div>
                             <div class="relative flex justify-center text-xs uppercase">
                                 <span class="bg-background dark:bg-background px-2 text-muted dark:text-muted">or</span>
                             </div>
                         </div>

                        <div class="grid gap-3">
                            <a href="/auth/google"
                                class="flex items-center justify-center gap-2 rounded-md border border-border dark:border-border px-4 py-2 text-sm font-medium text-foreground dark:text-foreground hover:bg-neutral-light dark:hover:bg-neutral-dark transition">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5" />
                                Continue with Google
                            </a>
                        </div>
                    </div>

                    <div class="text-center text-sm text-muted dark:text-muted">
                        Don’t have an account?
                        <TextLink :href="route('register')">Sign up</TextLink>
                    </div>
                </form>
            </AuthBase>
        </div>

        <div class="hidden lg:block relative">
            <video class="absolute inset-0 w-full h-full object-cover" src="/vid/nervdark.mp4" autoplay muted loop></video>
            <div class="absolute inset-0 bg-gradient-to-r from-background/90 dark:from-background/90 to-transparent"></div>
        </div>
    </div>
</template>

