<script setup>
import '@vuepic/vue-datepicker/dist/main.css'
import VueDatePicker from '@vuepic/vue-datepicker';
import {PlusIcon} from "@heroicons/vue/24/solid/index.js";
import {ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import TimesheetSort from "@/Components/TimesheetSort.vue";

const date = ref([]);

defineProps({
    datePlaceholder: {
        type: Date,
        required: true
    }
});

function handleDate(range) {
    if (range && range.length !== 2) {
        return;
    }

    router.visit(route('dashboard'), {
        data: {
            dateRange: range
        }
    });
}

function createTask() {
    router.visit(route('timesheet.create'), {
        data: {
            title: 'Новая задача',
            description: 'Описание'
        }
    });
}
</script>

<template>
    <div class="flex space-x-2">
        <button type="submit"
                class="flex items-center bg-space-600 text-white font-semibold rounded whitespace-nowrap py-1 px-2"
                @click.prevent.stop="createTask">
            <PlusIcon class="w-5 h-5"></PlusIcon>
            Создать задачу
        </button>

        <timesheet-sort />

        <VueDatePicker v-model="date" @update:model-value="(v) => handleDate(v)"
                       :placeholder="datePlaceholder"
                       :timezone="usePage().props.timezone"
                       :enable-time-picker="false"
                       :locale="usePage().props.locale" range/>
    </div>
</template>

<style scoped>

</style>
