<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Password from 'primevue/password'
import InputText from 'primevue/inputtext';
import Swal from 'sweetalert2';

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
</script>


<template>
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 text-white">
    <!-- Left Side: Login Form -->
    <div class="flex flex-col justify-center px-8 sm:px-16 lg:px-24">
      <AuthBase title="Log in to your account" description="Enter your username and password below to log in">

        <Head title="Log in" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
          <div class="grid gap-6">
            <!-- Username / Email -->
            <div class="grid gap-2">
              <Label for="login">Username atau Email</Label>
              <InputText id="login" type="text" required autofocus :tabindex="1"
                v-model="form.login"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                placeholder="Username / Email" />
              <InputError :message="form.errors.login" />
            </div>

            <!-- Password -->
            <div class="grid gap-2">
              <div class="flex items-center justify-between">
                <Label for="password">Password</Label>
                <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm">Forgot password?</TextLink>
              </div>
              <Password id="password" v-model="form.password"
                inputClass="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2"
                :feedback="false" toggleMask placeholder="Password" />
              <InputError :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
              <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
              <Label for="remember">Remember me</Label>
            </div>

            <!-- Button -->
            <Button type="submit" class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
              :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Log in
            </Button>

            <!-- Divider -->
            <div class="relative my-6">
              <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-gray-700"></span>
              </div>
              <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-black px-2 text-gray-400">or</span>
              </div>
            </div>

            <!-- OAuth Buttons -->
            <div class="grid gap-3">
              <a href="/auth/google"
                class="flex items-center justify-center gap-2 rounded-md border border-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-800 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5" />
                Continue with Google
              </a>
            </div>
          </div>

          <div class="text-center text-sm text-gray-400">
            Don’t have an account?
            <TextLink :href="route('register')">Sign up</TextLink>
          </div>
        </form>
      </AuthBase>
    </div>

    <!-- Right Side: Video / Background -->
    <div class="hidden lg:block relative">
      <video class="absolute inset-0 w-full h-full object-cover" src="/vid/logins.mp4" autoplay muted loop></video>
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
    </div>
  </div>
</template>

