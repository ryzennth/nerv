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
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 text-white">
    <!-- Left Side: Register Form -->
    <div class="flex flex-col justify-center px-8 sm:px-16 lg:px-24">
      <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
          <div class="grid gap-6">
            <!-- Fullname -->
            <div class="grid gap-2">
              <Label for="name">Fullname</Label>
              <InputText id="name" v-model="form.name" placeholder="Enter your fullname"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.name" />
            </div>

            <!-- Username -->
            <div class="grid gap-2">
              <Label for="username">Username</Label>
              <InputText id="username" v-model="form.username" placeholder="Enter your username"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.username ?? usernameWarning" />
            </div>

            <!-- Email -->
            <div class="grid gap-2">
              <Label for="email">Email</Label>
              <InputText id="email" v-model="form.email" type="email" placeholder="email@example.com"
                class="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" />
              <InputError :message="form.errors.email" />
            </div>

            <!-- Password -->
                            <div class="grid gap-2 w-full">
                    <Label for="password">Password</Label>
                    <Password id="password" type="password" v-model="form.password" placeholder="Password" :tabindex="3"
                        autocomplete="new-password" toggleMask inputClass="w-full" class="w-full">
                        <template #footer>
                            <Divider />
                            <ul class="pl-2 my-0 leading-normal text-sm">
                                <li v-if="!rules.minLength">Password minimal 8 karakter</li>
                                <li v-if="!rules.hasUppercase">Harus ada huruf besar</li>
                                <li v-if="!rules.hasLowercase">Harus ada huruf kecil</li>
                                <li v-if="!rules.hasNumber">Harus ada angka</li>
                                <li v-if="!rules.hasSymbol">Harus ada simbol (!@#$%^&*)</li>
                                <li v-if="rules.hasSpace">Password Tidak Boleh Menggunakan Spasi</li>
                            </ul>
                        </template>
                    </Password>

                    <InputError :message="form.errors.password" />
                </div>

            <!-- Confirm Password -->
            <div class="grid gap-2">
              <Label for="password_confirmation">Confirm password</Label>
              <Password id="password_confirmation" v-model="form.password_confirmation"
                placeholder="Confirm password" toggleMask inputClass="w-full rounded-md bg-transparent border border-gray-700 px-3 py-2"
                class="w-full" :feedback="false" />
              <InputError :message="form.errors.password_confirmation" />
            </div>

            <!-- reCAPTCHA -->
            <div class="g-recaptcha" :data-sitekey="sitekey"></div>

            <!-- Submit -->
            <Button type="submit"
              class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg"
              :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Create account
            </Button>
          </div>

          <div class="text-center text-sm text-gray-400">
            Already have an account?
            <TextLink :href="route('login')" class="underline underline-offset-4">Log in</TextLink>
          </div>
        </form>
      </AuthBase>
    </div>

    <!-- Right Side: Background Image / Video -->
    <div class="hidden lg:block relative">
      <video class="absolute inset-0 w-full h-full object-cover" src="/vid/haru.mp4" autoplay muted loop></video>
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
    </div>
  </div>
</template>

