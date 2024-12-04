<script setup>

import Dropdown from "@/Components/defaultUI/Dropdown.vue";
import DropdownLink from "@/Components/defaultUI/DropdownLink.vue";
import {computed} from "vue";
import {router, usePage} from "@inertiajs/vue3";

const selectedUser = usePage().props?.sort?.user;
const users = usePage().props.userMap;

const displayName = computed(() => `Задачи ${selectedUser ? users[selectedUser] : "Все"}`);

// Exclude the current user
const filteredUsers = computed(() => {
    return Object.fromEntries(
        Object.entries(users).filter(([id]) => parseInt(id) !== selectedUser)
    );
});

const hasUsers = computed(() => Object.keys(filteredUsers).length > 0);

const sortByUser = (userId) => {
    const currentParams = new URLSearchParams(window.location.search);
    if (userId) {
        currentParams.set('sort[user]', userId);
    } else {
        currentParams.delete('sort[user]');
    }

    router.visit(route('dashboard'), {
        data: currentParams
    });
};
</script>

<template>
    <Dropdown v-if="hasUsers">
        <template #trigger>
                <span class="inline-flex rounded-md">
                    <button type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                        {{ displayName }}

                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/>
                        </svg>
                    </button>
                </span>
        </template>

        <template #content>
            <div class="w-full">
                <DropdownLink as="a" href="#" @click="sortByUser(null)">Все</DropdownLink>
                <DropdownLink
                    v-for="(userName, userId) in filteredUsers"
                    :key="userId"
                    as="a"
                    href="#"
                    @click="sortByUser(userId)"
                >
                    {{ userName }}
                </DropdownLink>
            </div>
        </template>
    </Dropdown>
</template>

<style scoped>

</style>
