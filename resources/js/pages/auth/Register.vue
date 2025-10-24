<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import { reactive, watch, ref, onMounted } from 'vue';
import Divider from 'primevue/divider';
import Swal from 'sweetalert2';
const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    'g-recaptcha-response': '',
});

const submit = () => {
  const token = grecaptcha.getResponse();
  if (!token) {
    Swal.fire({
      icon: 'warning',
      title: 'Oops!',
      text: 'Harap centang reCAPTCHA terlebih dahulu!',
    });
    return;
  }

form['g-recaptcha-response'] = token;


  form.post(route('register'), {
    onError: (errors) => {
      console.log(errors);
    },
    onFinish: () => {
      grecaptcha.reset(); // reset captcha biar bisa dicentang lagi
    },
  });
};

const usernameWarning = ref('');
watch(() => form.username, (val) => {
    if (/\s/.test(val)) {
        usernameWarning.value = 'Username field cannot use spaces'
    } else {
        usernameWarning.value = ''
    }
})
// realtime validate password
const rules = reactive({
    minLength: false,
    hasUppercase: false,
    hasLowercase: false,
    hasNumber: false,
    hasSymbol: false,
    hasSpace: false
})
function validatePassword() {
    const val = form.password

    rules.minLength = val.length >= 8
    rules.hasUppercase = /[A-Z]/.test(val)
    rules.hasLowercase = /[a-z]/.test(val)
    rules.hasNumber = /[0-9]/.test(val)
    rules.hasSymbol = /[^A-Za-z0-9]/.test(val)
    rules.hasSpace = /\s/.test(val)
}
watch(() => form.password, validatePassword)
onMounted(() => {
    const recaptchaScriptId = 'recaptcha-script';

    if (!document.getElementById(recaptchaScriptId)) {
        const script = document.createElement('script');
        script.id = recaptchaScriptId;
        script.src = 'https://www.google.com/recaptcha/api.js';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
});
const sitekey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;
</script>

<template>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-background dark:bg-background text-foreground dark:text-foreground">
        <div class="flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12"> <AuthBase title="Create an account" description="Enter your details below to create your account">
                <Head title="Register" />

                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="name">Fullname</Label>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                placeholder="Enter your fullname"
                                required
                                class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="username">Username</Label>
                             <input
                                id="username"
                                type="text"
                                v-model="form.username"
                                placeholder="Enter your username"
                                required
                                class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                            />
                            <InputError :message="form.errors.username ?? usernameWarning" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                             <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                placeholder="email@example.com"
                                required
                                class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="grid gap-2 w-full">
                            <Label for="password">Password</Label>
                            <div class="relative">
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    placeholder="Password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-muted dark:text-muted hover:text-foreground dark:hover:text-foreground focus:outline-none"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.012-.001.024 0 .036C20.268 16.057 16.477 19 12 19c-4.478 0-8.268-2.943-9.542-7-.001-.012-.001-.024 0-.036z" /></svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .5-1.576 1.4-3.003 2.5-4.2M19.542 12c-.5 1.575-1.4 3-2.5 4.2M10.125 12a2 2 0 11-4 0 2 2 0 014 0zM19 19L5 5" /></svg>
                                </button>
                            </div>
                            <ul class="pl-2 my-1 text-xs text-muted dark:text-muted list-disc list-inside space-y-1">
                                <li :class="{ 'text-green-500': rules.minLength }">Password minimal 8 karakter</li>
                                <li :class="{ 'text-green-500': rules.hasUppercase }">Harus ada huruf besar</li>
                                <li :class="{ 'text-green-500': rules.hasLowercase }">Harus ada huruf kecil</li>
                                <li :class="{ 'text-green-500': rules.hasNumber }">Harus ada angka</li>
                                <li :class="{ 'text-green-500': rules.hasSymbol }">Harus ada simbol (!@#$%^&*)</li>
                                <li :class="{ 'text-red-500': rules.hasSpace }">Password Tidak Boleh Menggunakan Spasi</li>
                            </ul>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirm password</Label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    placeholder="Confirm password"
                                    required
                                    class="w-full rounded-md bg-transparent border border-border dark:border-border px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-(--ring) dark:focus:ring-(--ring)"
                                />
                                 <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-muted dark:text-muted hover:text-foreground dark:hover:text-foreground focus:outline-none"
                                    aria-label="Toggle confirm password visibility"
                                >
                                     <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.012-.001.024 0 .036C20.268 16.057 16.477 19 12 19c-4.478 0-8.268-2.943-9.542-7-.001-.012-.001-.024 0-.036z" /></svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .5-1.576 1.4-3.003 2.5-4.2M19.542 12c-.5 1.575-1.4 3-2.5 4.2M10.125 12a2 2 0 11-4 0 2 2 0 014 0zM19 19L5 5" /></svg>
                                </button>
                            </div>
                            <InputError :message="form.errors.password_confirmation" />
                        </div>

                        <div class="g-recaptcha" :data-sitekey="sitekey"></div>

                        <Button type="submit"
                            class="mt-4 w-full bg-(--brand) hover:opacity-80 text-primary-foreground font-medium py-2 rounded-lg"
                            :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="inline-block mr-2 h-4 w-4 animate-spin" />
                            Create account
                        </Button>
                    </div>

                     <div class="text-center text-sm text-muted dark:text-muted">
                        Already have an account?
                        <TextLink :href="route('login')" class="font-medium underline underline-offset-4">Log in</TextLink>
                    </div>
                </form>
            </AuthBase>
        </div>

        <div class="hidden lg:block relative">
            <video class="absolute inset-0 w-full h-full object-cover" src="/vid/logins.mp4" autoplay muted loop></video>
             <div class="absolute inset-0 bg-gradient-to-r from-background/90 dark:from-background/90 to-transparent"></div>
        </div>
    </div>
</template>

