<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/defaultUI/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/defaultUI/ApplicationLogo.vue';
import Checkbox from '@/Components/defaultUI/Checkbox.vue';
import InputError from '@/Components/defaultUI/InputError.vue';
import InputLabel from '@/Components/defaultUI/InputLabel.vue';
import PrimaryButton from '@/Components/defaultUI/PrimaryButton.vue';
import TextInput from '@/Components/defaultUI/TextInput.vue';
import DangerButton from "@/Components/defaultUI/DangerButton.vue";
import SecondaryButton from "@/Components/defaultUI/SecondaryButton.vue";

defineProps({
    canRegister: Boolean,
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Авторизация" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Пароль" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Запомнить меня?</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link class="ms-4" :href="route('register')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Регистрация
                </Link>
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Вход
                </PrimaryButton>
            </div>
        </form>

<!--        <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
<!--            Забыли ваш пароль?-->
<!--        </Link>-->
    </AuthenticationCard>
</template>
